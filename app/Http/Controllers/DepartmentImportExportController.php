<?php

namespace App\Http\Controllers;

use App\Exports\DepartmentExport;
use App\Imports\DepartmentImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentImportExportController extends Controller
{
    public function import(Request $request)
    {

        $request->validate([
            'excel_file' => 'required|mimes:xlsx'
        ]);
        Excel::import(new DepartmentImport(),$request->file('excel_file'));
        return redirect()->back();
    }
    public function export()
    {
        return Excel::download(new DepartmentExport(), 'departments.xlsx');
    }
}
