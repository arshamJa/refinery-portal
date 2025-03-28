<?php

namespace Database\Seeders;


use App\Models\UserInfo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
        $this->call([UserSeeder::class,PermissionSeeder::class]);
        \App\Models\Blog::factory(10)->create();
        UserInfo::factory(20)->create();
    }
}
