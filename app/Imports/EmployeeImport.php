<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\OperatorPhones;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
//        $department = Department::where('name', $row['department_name'])->first();
//        if (!$department) {
//            return null;
//        }
        return new OperatorPhones([
//            'department_id' => $department->id,
            'department_id' => $row['department_id'],
            'full_name'     => $row['full_name'],
            'phone'         => $row['phone'],
            'house_phone'   => $row['house_phone'],
            'work_phone'    => $row['work_phone'],
            'n_code'        => $row['n_code'],
            'p_code'        => $row['p_code'],
            'position'      => $row['position'],
        ]);
    }
}
