<?php

namespace App\Http\Controllers;

use App\Exports\OrganizationExport;
use App\Imports\OrganizationImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrganizationImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required'
        ]);
        Excel::import(new OrganizationImport(),$request->file('excel_file'));
        return redirect()->route('organizations')->with('status','سامانه با موفقیت آپلود شد');
    }
    public function export()
    {
        return new OrganizationExport();
    }
}
