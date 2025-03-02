<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class ScriptoriumReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Meeting::with('tasks');
        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate);
        }
        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('scriptorium', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('date', 'like', '%' . $search . '%');
            });
        }
        if (auth()->check() && auth()->user()->user_info) {
            $query->where('scriptorium', '=', auth()->user()->user_info->full_name);
        }
        $query->where('is_cancelled', '=', '-1');
        $meetings = $query->paginate(5);
        return view('reports.scriptorium-report', [
            'meetings' => $meetings]);
    }
}
