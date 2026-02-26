<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContestantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nickname' => fake()->firstName(),
            'description' => fake()->sentence(),
            'photo' => null,
            'contestant_number' => fake()->unique()->numberBetween(1, 100),
        ];
    }
}
