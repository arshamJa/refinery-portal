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
        // Create roles
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $adminRole = Role::create(['name' => 'ادمین']);
        $operatorRole = Role::create(['name' => 'اپراتور']);
        $userRole = Role::create(['name' => 'کاربر']);

        // Create permissions
        $editArticlesPermission = Permission::create(['name' => 'ایجاد جلسه']);
        $deleteUsersPermission = Permission::create(['name' => 'ایجاد جلسه']);

        // Assign permissions to the admin role
        $superAdminRole->givePermissionTo($editArticlesPermission);
        $superAdminRole->givePermissionTo($deleteUsersPermission);
        $adminRole->givePermissionTo($editArticlesPermission);
        $adminRole->givePermissionTo($deleteUsersPermission);

        // Assign permissions to the editor role
        $operatorRole->givePermissionTo($editArticlesPermission);
        $userRole->givePermissionTo($editArticlesPermission);

        // Assign the admin role to a user
        $user = User::find(1); // Replace 1 with the user ID
        if (!$user) {
            // User with ID 1 does not exist. Create one, or handle the error.
            $user = User::create([
                'password' => Hash::make('Samael'),
                'p_code' => Hash::make('SamaelProgrammer'),
            ]);
        }
        $user->assignRole($superAdminRole);

    }
}
