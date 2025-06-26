<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                'password' => 'Samael.Programmer',
                'p_code' => Hash::make('Samael'),
            ]);
        }
        // Create Roles
        $superAdminRole = Role::create(['name' => UserRole::SUPER_ADMIN->value]);
        $adminRole = Role::create(['name' => UserRole::ADMIN->value]);
        $operatorRole = Role::create(['name' => UserRole::OPERATOR->value]);
        $userRole = Role::create(['name' => UserRole::USER->value]);

        // Assign Role to Super Admin User
        $superAdminUser->assignRole($superAdminRole);

        // Create Permissions
        $meetingCreate = Permission::create(['name' => 'ایجاد جلسه']);

        // Assign Permissions to Roles
        $superAdminRole->assignPermission($meetingCreate);


        $users = User::whereNotIn('id', [1, 2, 3, 4])->get();
        foreach ($users as $user) {
            $user->assignRole($userRole);
        }

    }
}
