<?php

namespace App\Exports;

use App\Models\Organization;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class OrganizationExport implements FromCollection, Responsable
{
    use Exportable;
    private $fileName = 'organizations.xlsx';
    private $writerType = Excel::XLSX;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Organization::all();
    }
}
