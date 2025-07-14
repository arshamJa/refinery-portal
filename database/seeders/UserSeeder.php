<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_infos')->insert([
            'user_id' => 1,
            'department_id' => 1,
            'full_name' => 'Arsham Jamali',
            'work_phone' => 'null',
            'house_phone' => 'null',
            'phone' => 'null',
            'n_code' => '3490449401',
            'position' => 'Programmer',
        ]);
    }
}
