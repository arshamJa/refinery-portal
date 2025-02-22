<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScriptoriumReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $query = Meeting::query();
        if ($startDate && $endDate) {
            $query->where('date', '>', $startDate)
            ->where('date', '<', $endDate);
        } elseif ($startDate) {
            $query->where('date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('date', '<=', $endDate);
        }
        $results = $query->paginate(5);
        return view('scriptorium-report.index', ['results' => $results]);
    }

    public function showAll(Request $request)
    {
        return view('scriptorium-report.index', ['results' => $results]);
    }
}
