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
           'role' => 'admin',
            'password' => Hash::make('12345678'),
            'p_code' => fake()->randomNumber(6, true),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'role' => 'operator_news',
            'password' => Hash::make('12345678'),
            'p_code' => fake()->randomNumber(6, true),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'role' => 'operator_phones',
            'password' => Hash::make('12345678'),
            'p_code' => fake()->randomNumber(6, true),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
