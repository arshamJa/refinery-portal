<?php

namespace App\Imports;

//use App\Models\User;
//use App\Models\UserInfo;
//use App\Models\Department;
//use App\Models\OperatorPhones;
//use Maatwebsite\Excel\Concerns\ToCollection;
//use Maatwebsite\Excel\Concerns\WithHeadingRow;
//use Illuminate\Support\Collection;
//
//class UserImport implements ToCollection, WithHeadingRow
//{
//    public function collection(Collection $rows)
//    {
//        foreach ($rows as $row) {
//
//            // Force p_code as string
//            $pCode = isset($row['p_code']) ? trim((string)$row['p_code']) : null;
//
//            // Skip if no p_code
//            if (!$pCode) continue;
//
//            // Department
//            $departmentId = null;
//            if (!empty($row['department'])) {
//                $department = Department::firstOrCreate([
//                    'department_name' => trim($row['department']),
//                ]);
//                $departmentId = $department->id;
//            }
//
//            // User
//            $user = User::updateOrCreate(
//                ['p_code' => $pCode],
//                ['password' => $row['password'] ?? '123456t']
//            );
//
//            // UserInfo
//            UserInfo::updateOrCreate(
//                ['user_id' => $user->id],
//                [
//                    'department_id' => $departmentId,
//                    'full_name'     => $row['full_name'] ?? null,
//                    'work_phone'    => $row['work_phone'] ?? null,
//                    'house_phone'   => $row['house_phone'] ?? null,
//                    'phone'         => $row['phone'] ?? null,
//                    'n_code'        => $row['n_code'] ?? null,
//                    'position'      => $row['position'] ?? null,
//                ]
//            );
//
//            // Create the new operator phone record
//            OperatorPhones::create([
//                'department_id' => $departmentId,
//                'full_name'     => $row['full_name'] ?? null,
//                'work_phone'    => $row['work_phone'] ?? null,
//                'house_phone'   => $row['house_phone'] ?? null,
//                'phone'         => $row['phone'] ?? null,
//                'n_code'        => $row['n_code'] ?? null,
//                'p_code'        => $pCode,
//                'position'      => $row['position'] ?? null,
//            ]);
//        }
//    }
//}


//use App\Models\Department;
//use App\Models\OperatorPhones;
//use App\Models\User;
//use App\Models\UserInfo;
//use Illuminate\Support\Collection;
//use Illuminate\Support\Facades\Hash;
//use Maatwebsite\Excel\Concerns\ToCollection;
//use Maatwebsite\Excel\Concerns\WithHeadingRow;
//
//class UserImport implements ToCollection, WithHeadingRow
//{
//    public function collection(Collection $rows)
//    {
//        $departments = [];
//        $users = [];
//        $userInfos = [];
//        $operatorPhones = [];
//        $existingDepartments = Department::pluck('id', 'department_name')->toArray();
//
//        foreach ($rows as $row) {
//            $pCode = isset($row['p_code']) ? trim((string)$row['p_code']) : null;
//            if (!$pCode) continue;
//
//            // Departments
//            $departmentName = trim($row['department'] ?? '');
//            $departmentId = $existingDepartments[$departmentName] ?? null;
//            if ($departmentName && !$departmentId) {
//                $departments[$departmentName] = $departmentName; // collect new departments
//            }
//
//            // Users
//            $users[$pCode] = [
//                'p_code' => $pCode,
//                'password' => Hash::make($row['password'] ?? '123456t'),
//                'updated_at' => now(),
//                'created_at' => now(),
//            ];
//
//            // Store user info temporarily, department_id will be updated later
//            $userInfos[$pCode] = [
//                'department_name' => $departmentName, // keep name temporarily
//                'full_name'     => $row['full_name'] ?? null,
//                'work_phone'    => $row['work_phone'] ?? null,
//                'house_phone'   => $row['house_phone'] ?? null,
//                'phone'         => $row['phone'] ?? null,
//                'n_code'        => $row['n_code'] ?? null,
//                'position'      => $row['position'] ?? null,
//                'updated_at'    => now(),
//                'created_at'    => now(),
//            ];
//
//            // OperatorPhones (append-only)
//            $operatorPhones[] = [
//                'p_code'        => $pCode,
//                'phone'         => $row['phone'] ?? null,
//                'department_id' => $departmentId,
//                'full_name'     => $row['full_name'] ?? null,
//                'work_phone'    => $row['work_phone'] ?? null,
//                'house_phone'   => $row['house_phone'] ?? null,
//                'n_code'        => $row['n_code'] ?? null,
//                'position'      => $row['position'] ?? null,
//                'updated_at'    => now(),
//                'created_at'    => now(),
//            ];
//        }
//
//        // Insert new departments and update existingDepartments array
//        foreach ($departments as $name) {
//            $department = Department::create(['department_name' => $name]);
//            $existingDepartments[$name] = $department->id;
//        }
//
//        // Bulk upsert users
//        User::upsert(array_values($users), ['p_code'], ['password', 'updated_at']);
//
//        // Get user IDs after upsert
//        $userIds = User::pluck('id', 'p_code')->toArray();
//
//        // Upsert UserInfo with correct department_id
//        foreach ($userInfos as $pCode => $data) {
//            $departmentName = $data['department_name'] ?? null;
//            $departmentId = $existingDepartments[$departmentName] ?? null;
//
//            $userId = $userIds[$pCode] ?? null;
//            if ($userId) {
//                UserInfo::updateOrCreate(
//                    ['user_id' => $userId],
//                    [
//                        'department_id' => $departmentId,
//                        'full_name'     => $data['full_name'],
//                        'work_phone'    => $data['work_phone'],
//                        'house_phone'   => $data['house_phone'],
//                        'phone'         => $data['phone'],
//                        'n_code'        => $data['n_code'],
//                        'position'      => $data['position'],
//                    ]
//                );
//            }
//        }
//
//        // Insert OperatorPhones (append-only)
//        OperatorPhones::insert($operatorPhones);
//    }
//}

use App\Models\Department;
use App\Models\OperatorPhones;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $departmentsToInsert = [];
        $users = [];
        $userInfos = [];
        $operatorPhones = [];

        // Existing departments
        $existingDepartments = Department::pluck('id', 'department_name')->toArray();

        foreach ($rows as $row) {
            $pCode = isset($row['p_code']) ? trim((string)$row['p_code']) : null;
            if (!$pCode) continue;

            // Collect departments
            $departmentName = trim($row['department'] ?? '');
            if ($departmentName && !isset($existingDepartments[$departmentName])) {
                $departmentsToInsert[$departmentName] = $departmentName;
            }

            // Prepare users
            $users[$pCode] = [
                'p_code' => $pCode,
                'password' => Hash::make($row['password'] ?? '123456t'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Keep department name temporarily
            $userInfos[$pCode] = [
                'department_name' => $departmentName,
                'full_name'       => $row['full_name'] ?? null,
                'work_phone'      => $row['work_phone'] ?? null,
                'house_phone'     => $row['house_phone'] ?? null,
                'phone'           => $row['phone'] ?? null,
                'n_code'          => $row['n_code'] ?? null,
                'position'        => $row['position'] ?? null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ];

            // Operator phones
            $operatorPhones[] = [
                'p_code'        => $pCode,
                'phone'         => $row['phone'] ?? null,
                'department_name'=> $departmentName, // temp store
                'full_name'     => $row['full_name'] ?? null,
                'work_phone'    => $row['work_phone'] ?? null,
                'house_phone'   => $row['house_phone'] ?? null,
                'n_code'        => $row['n_code'] ?? null,
                'position'      => $row['position'] ?? null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // Insert new departments in bulk
        if (!empty($departmentsToInsert)) {
            $insertData = array_map(fn($name) => ['department_name' => $name, 'created_at' => now(), 'updated_at' => now()], $departmentsToInsert);
            Department::insert($insertData);

            // Update existingDepartments array
            $existingDepartments = Department::pluck('id', 'department_name')->toArray();
        }

        // Upsert users in bulk
        User::upsert(array_values($users), ['p_code'], ['password', 'updated_at']);

        // Get user IDs in memory
        $userIds = User::pluck('id', 'p_code')->toArray();

        // Prepare bulk UserInfo
        $bulkUserInfos = [];
        foreach ($userInfos as $pCode => $data) {
            $userId = $userIds[$pCode] ?? null;
            if ($userId) {
                $departmentId = $existingDepartments[$data['department_name']] ?? null;
                $bulkUserInfos[] = [
                    'user_id'       => $userId,
                    'department_id' => $departmentId,
                    'full_name'     => $data['full_name'],
                    'work_phone'    => $data['work_phone'],
                    'house_phone'   => $data['house_phone'],
                    'phone'         => $data['phone'],
                    'n_code'        => $data['n_code'],
                    'position'      => $data['position'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }
        }

        // Upsert UserInfo in bulk
        foreach (array_chunk($bulkUserInfos, 500) as $chunk) {
            UserInfo::upsert($chunk, ['user_id'], ['department_id', 'full_name', 'work_phone', 'house_phone', 'phone', 'n_code', 'position', 'updated_at']);
        }

        // Prepare OperatorPhones with correct department_id
        foreach ($operatorPhones as &$op) {
            $op['department_id'] = $existingDepartments[$op['department_name']] ?? null;
            unset($op['department_name']);
        }

        // Insert OperatorPhones in chunks
        foreach (array_chunk($operatorPhones, 500) as $chunk) {
            OperatorPhones::insert($chunk);
        }
    }
}

