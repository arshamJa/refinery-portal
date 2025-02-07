<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserInfo>
 */
class UserInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'position' => fake()->name() ,
            'department_id' => 1,
            'n_code' => fake()->randomKey(['3490449401'=>1, '6090117854'=>2, '3333333333'=>3, '4444444444'=>4]),
            'work_phone' => fake()->phoneNumber(),
            'house_phone' => fake()->phoneNumber(),
            'phone' => fake()->phoneNumber(),
            'full_name' => fake('fa_IR')->name(),
        ];
    }
}
