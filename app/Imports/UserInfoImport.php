<?php

namespace App\Imports;

use App\Models\OperatorPhones;
use App\Models\User;
use App\Models\UserInfo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class UserInfoImport implements ToModel, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $data = $row->toArray();

        $userId = null;

        if (!empty($data['user_id'])) {
            $userId = $data['user_id'];
        } elseif (!empty($data['p_code'])) {
            $user = User::where('p_code', $data['p_code'])->first();
            if ($user) {
                $userId = $user->id;
            } else {
                return;
            }
        } else {
            return;
        }

        // Insert into user_infos
        UserInfo::create([
            'user_id' => $userId,
            'department_id' => null,
            'full_name' => $data['full_name'] ?? null,
            'work_phone' => $data['work_phone'] ?? null,
            'house_phone' => $data['house_phone'] ?? null,
            'phone' => $data['phone'] ?? null,
            'n_code' => $data['n_code'] ?? null,
            'position' => $data['position'] ?? null,
            'signature' => null,
        ]);

        // Insert into operator_phones
        OperatorPhones::create([
            'department_id' => null,
            'full_name' => $data['full_name'] ?? null,
            'work_phone' => $data['work_phone'] ?? null,
            'house_phone' => $data['house_phone'] ?? null,
            'phone' => $data['phone'] ?? null,
            'n_code' => $data['n_code'] ?? null,
            'p_code' => $data['p_code'] ?? null,
            'position' => $data['position'] ?? null,
        ]);
    }
}
