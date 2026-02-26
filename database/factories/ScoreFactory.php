<?php

namespace Database\Factories;

use App\Models\Contestant;
use App\Models\Criteria;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScoreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'judge_id' => User::factory(),
            'contestant_id' => Contestant::factory(),
            'criteria_id' => Criteria::factory(),
            'score' => fake()->randomFloat(2, 50, 100),
            'remarks' => fake()->optional(0.3)->sentence(),
        ];
    }
}
