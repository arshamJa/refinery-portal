<?php

namespace App\Exports;

use App\Models\Department;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Excel;

class DepartmentExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Department::all();
    }
}
