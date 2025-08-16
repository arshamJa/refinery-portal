<?php

namespace Database\Seeders;

use App\Enums\UserPermission;
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
            $superAdminUser = User::firstOrCreate(
                ['p_code' => 'Samael'],
                ['password' => env('SUPER_ADMIN_PASSWORD')]
            );
        }

        // Create Roles
        $superAdminRole = Role::create(['name' => UserRole::SUPER_ADMIN->value]);
        $adminRole = Role::create(['name' => UserRole::ADMIN->value]);
        $operatorRole = Role::create(['name' => UserRole::OPERATOR->value]);
        $userRole = Role::create(['name' => UserRole::USER->value]);

        // Assign Role to Super Admin User
        $superAdminUser->assignRole($superAdminRole);

       foreach (UserPermission::cases() as $perm){
           Permission::firstOrCreate(['name' => $perm->value]);
       }

    }
}
