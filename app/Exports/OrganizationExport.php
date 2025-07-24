<?php

namespace App\Exports;

use App\Models\Organization;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class OrganizationExport implements FromCollection, WithHeadings, WithMapping, Responsable
{
    use Exportable;

    private $fileName = 'organizations.xlsx';
    private $writerType = \Maatwebsite\Excel\Excel::XLSX;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    private $rowNumber = 0;

    /**
     * Return all organizations with their department loaded
     */
    public function collection()
    {
        return Organization::with('department')->get();
    }

    /**
     * Define Excel column headings
     */
    public function headings(): array
    {
        return [
            'ردیف',              // Row number
            'نام واحد سازمانی',   // department_name
            'نام سامانه',         // organization
            'لینک سامانه',        // url
        ];
    }

    /**
     * Map each organization to the desired format
     */
    public function map($organization): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $organization->department->department_name ?? '---',
            $organization->organization_name,
            $organization->url,
        ];
    }
}
