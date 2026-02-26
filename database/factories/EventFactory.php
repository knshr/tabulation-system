<?php

namespace Database\Factories;

use App\Enums\EventStatus;
use App\Enums\ScoringMode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true).' Competition',
            'description' => fake()->paragraph(),
            'venue' => fake()->address(),
            'event_date' => fake()->dateTimeBetween('now', '+3 months'),
            'status' => EventStatus::Draft,
            'scoring_mode' => ScoringMode::Blind,
            'created_by' => User::factory(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => EventStatus::Active]);
    }

    public function completed(): static
    {
        return $this->state(fn () => ['status' => EventStatus::Completed]);
    }
}
