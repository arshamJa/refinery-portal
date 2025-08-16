<?php

namespace App\Imports;

use App\Exports\DepartmentExport;
use App\Models\Department;
use App\Models\Organization;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DepartmentImport implements ToModel,WithHeadingRow
{
    private $current = 0;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }
    public function model(array $row)
    {
        $this->current++;
        if($this->current > 1){
            $count = Department::where('department_name' , '=',$row[1])->count();
            if (empty($count)){
                $department = new Department();
                $department->department_name = $row[1];
                $department->save();
            }
        }
    }
}
