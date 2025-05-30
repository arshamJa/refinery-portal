<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'password' => Hash::make($row['password']),
            'p_code' => $row['p_code'],
            'profile_photo_path' => $row['profile_photo_path'],
        ]);
    }
}
