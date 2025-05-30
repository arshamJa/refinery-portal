<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserInfoImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Find the user using p_code instead of email
            $user = User::where('p_code', $row['p_code'])->first();

            if ($user) {
                UserInfo::create([
                    'user_id' => $user->id,
                    'department_id' => null,
                    'full_name' => $row['full_name'],
                    'work_phone' => $row['work_phone'],
                    'house_phone' => $row['house_phone'],
                    'phone' => $row['phone'],
                    'n_code' => $row['n_code'],
                    'position' => $row['position'],
                    'signature' => null,
                ]);
            }
        }
    }
}
