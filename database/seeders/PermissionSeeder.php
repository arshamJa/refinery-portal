<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
