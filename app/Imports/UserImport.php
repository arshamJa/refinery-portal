<?php

namespace App\Imports;

use App\Models\OperatorPhones;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class UserImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $data = $row->toArray();

        DB::transaction(function () use ($data) {
            // 🔹 Step 1: Handle User record
            $user = null;

            if (!empty($data['user_id'])) {
                $user = User::find($data['user_id']);
            } elseif (!empty($data['p_code'])) {
                $user = User::where('p_code', $data['p_code'])->first();
            }

            if (!$user) {
                // If no existing user, create one
                $user = User::create([
                    'p_code'   => $data['p_code'] ?? null,
                    'password' => $data['password'] ?? '123456t', // plain, auto-hashed
                ]);
            } else {
                // If user exists, update password if provided
                if (!empty($data['password'])) {
                    $user->update([
                        'password' => $data['password'], // plain, auto-hashed
                    ]);
                }
            }
            $userId = $user->id;

            // 🔹 Step 2: Insert/Update into user_infos
            UserInfo::updateOrCreate(
                ['user_id' => $userId],
                [
                    'department_id' => null,
                    'full_name'     => $data['full_name'] ?? null,
                    'work_phone'    => $data['work_phone'] ?? null,
                    'house_phone'   => $data['house_phone'] ?? null,
                    'phone'         => $data['phone'] ?? null,
                    'n_code'        => $data['n_code'] ?? null,
                    'position'      => $data['position'] ?? null,
                    'signature'     => null,
                ]
            );

            // 🔹 Step 3: Insert into operator_phones
            OperatorPhones::create([
                'department_id' => null,
                'full_name'     => $data['full_name'] ?? null,
                'work_phone'    => $data['work_phone'] ?? null,
                'house_phone'   => $data['house_phone'] ?? null,
                'phone'         => $data['phone'] ?? null,
                'n_code'        => $data['n_code'] ?? null,
                'p_code'        => $data['p_code'] ?? null,
                'position'      => $data['position'] ?? null,
            ]);
        });
    }
}
