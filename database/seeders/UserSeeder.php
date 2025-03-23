<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'password' => Hash::make('Samael'),
            'p_code' => Hash::make('SamaelProgrammer'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
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
