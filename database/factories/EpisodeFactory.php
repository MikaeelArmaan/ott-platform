<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Season;
use App\Models\Content;

class EpisodeFactory extends Factory
{
    public function definition(): array
    {
        return [

            'content_id' => Content::factory(),

            'season_id' => Season::factory(),

            'episode_number' => $this->faker->numberBetween(1,10),

            'title' => $this->faker->sentence(3),

            'description' => $this->faker->paragraph(),

            'duration' => $this->faker->numberBetween(2400,3600),

            'thumbnail' => 'https://picsum.photos/800/450?random=' . $this->faker->numberBetween(1,1000),

            'is_published' => true

        ];
    }
}