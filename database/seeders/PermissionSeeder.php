<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'super-admin',
            'admin',
            'boss',
            'scriptorium',
            'operator_news',
            'operator_blog',
            'employee',
        ];
        foreach ($roles as $key => $role){
            Role::create(['name' => $role]);

        }

        DB::table('model_has_roles')->insert([
            'role_id' => 1,
            'model_type' => 'App\Models\User',
            'model_id' => 1,
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => 2,
            'model_type' => 'App\Models\User',
            'model_id' => 2,
        ]);



        $permissions = [
          'create-meeting',
           'view-phone-list',
           'view-profile',
        ];
        foreach ($permissions as $key => $permission){
            Permission::updateOrCreate(['name'=>$permission]);
        }
    }
}
