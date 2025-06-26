<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $user = new User();
        $user->password = Hash::make($row['password']);
        $user->p_code = $row['p_code'];
        $user->save();
    }
}
