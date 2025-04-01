<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\UserRole;
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
                'password' => Hash::make('Samael'),
                'p_code' => Hash::make('SamaelProgrammer'),
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
        $rolePermissionTable = Permission::create(['name' => 'مدیریت نقش-دسترسی']);

        // Assign Permissions to Roles
        $superAdminRole->assignPermission($meetingCreate);
        $superAdminRole->assignPermission($rolePermissionTable);

    }
}
