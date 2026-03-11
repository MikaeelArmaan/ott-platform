<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Content;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Season>
 */
class SeasonFactory extends Factory
{
    public function definition(): array
    {
        $seasonNumber = $this->faker->numberBetween(1, 5);

        return [

            'content_id' => Content::factory(),

            'season_number' => $seasonNumber,

            'title' => "Season {$seasonNumber}",

            'description' => $this->faker->sentence()

        ];
    }
}
