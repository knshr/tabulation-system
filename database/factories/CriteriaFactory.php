<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class CriteriaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => fake()->randomElement(['Talent', 'Beauty', 'Intelligence', 'Poise', 'Presentation', 'Creativity']),
            'description' => fake()->sentence(),
            'max_score' => 100,
            'percentage_weight' => 20,
            'order' => 0,
        ];
    }
}
