<?php

namespace Database\Factories;

use App\Enums\MeetingStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class MeetingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('now', '+1 month');
        $startTime = $date->format('H:i');
        $endTime = fake()->boolean(70) ? fake()->time('H:i') : null; // 70% chance to have an end_time

        return [
            'title'          => fake()->sentence(3),
            'scriptorium_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'boss_id'        => User::inRandomOrder()->first()?->id ?? User::factory(),
            'location'       => fake()->address(),
            'date'           => $date->format('Y-m-d'),
            'time'           => $startTime,
            'end_time'       => $endTime,
            'unit_held'      => fake()->company(),
            'treat'          => fake()->boolean(),
            'guest'          => '',
            'status'         => fake()->randomElement([
                MeetingStatus::PENDING->value,
                MeetingStatus::IS_CANCELLED->value,
                MeetingStatus::IS_NOT_CANCELLED->value,
                MeetingStatus::IS_IN_PROGRESS->value,
                MeetingStatus::IS_FINISHED->value,
            ]),
        ];
    }
}
