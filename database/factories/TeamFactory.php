<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
          'created_at' => now(),
          'updated_at' => now(),
          'name' => fake()->name(),
          'player_quality' => rand(100,1000)/100,
          'audience_support' => rand(100,1000)/100,
        ];
    }
}
