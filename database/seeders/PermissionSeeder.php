<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $superAdminUser = User::find(1);
        if (!$superAdminUser) {
            $superAdminUser = User::create([
                'password' => 'Samael',
                'p_code' => Hash::make('SamaelProgrammer'),
            ]);
        }
        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'ادمین']);
        $operatorRole = Role::firstOrCreate(['name' => 'اپراتور']);
        $userRole = Role::firstOrCreate(['name' => 'کاربر']);

        // Create permissions
        $permissionMeeting = Permission::firstOrCreate(['name' => 'ایجاد جلسه']);

        // Assign permission to role
        $superAdminRole->assignPermission($permissionMeeting);
        $adminRole->assignPermission($permissionMeeting);

        // Assign the admin role to a user
        $superAdminUser->assignRole($superAdminRole);
    }
}
