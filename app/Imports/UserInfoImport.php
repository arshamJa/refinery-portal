<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserInfo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserInfoImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $userId = null;
        if ($row['user_id'] && !empty($row['user_id']) !== null) {
            $userId = $row['user_id'];
        } else {
            if (isset($row['p_code']) && !empty($row['p_code'])) {
                $user = User::where('p_code', $row['p_code'])->first();
                if ($user) {
                    $userId = $user->id;
                } else {
                    return null;
                }
            }else{
                return null;
            }
        }
        return new UserInfo([
            'user_id' => $userId,
            'department_id' => null,
            'full_name' => $row['full_name'] ?? null,
            'work_phone' => $row['work_phone'] ?? null,
            'house_phone' => $row['house_phone'] ?? null,
            'phone' => $row['phone'] ?? null,
            'n_code' => $row['n_code'] ?? null,
            'position' => $row['position'] ?? null,
            'signature' => null,
        ]);

    }
}
