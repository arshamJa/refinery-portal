<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PermissionSeeder::class,
        ]);
        \App\Models\Blog::factory(10)->create();
        \App\Models\Meeting::factory(10)->create();
    }
}
