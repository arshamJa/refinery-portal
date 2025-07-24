<?php

namespace App\Exports;

use App\Models\Department;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class DepartmentExport implements FromCollection, WithHeadings, WithMapping, Responsable
{
    use Exportable;

    /**
     * The name of the exported file.
     */
    private string $fileName = 'departments.xlsx';

    /**
     * The writer type (XLSX, CSV, etc.).
     */
    private string $writerType = \Maatwebsite\Excel\Excel::XLSX;

    /**
     * Additional HTTP headers for the response.
     */
    private array $headers = [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    /**
     * Row number counter for "ردیف" column.
     */
    private int $rowNumber = 0;

    /**
     * Return all organizations with their department loaded.
     */
    public function collection()
    {
        return Department::all();
    }

    /**
     * Define Excel column headings.
     */
    public function headings(): array
    {
        return [
            'ردیف',               // Row number
            'نام دپارتمان',    // department_name
        ];
    }

    /**
     * Map each organization to the desired export format.
     */
    public function map($department): array
    {
        return [
            ++$this->rowNumber,
            $department->department_name ?? '---',
        ];
    }
}
