<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContentFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement(['movie','series']);

        return [
            'type' => $type,
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'language' => $this->faker->randomElement(['English','Hindi','Spanish','French']),
            'release_date' => $this->faker->date(),
            'runtime_seconds' => $type === 'movie'
                ? $this->faker->numberBetween(3600, 9000)
                : null,

            'poster_url' =>
                'https://picsum.photos/300/450?random=' .
                $this->faker->unique()->numberBetween(1,9999),

            'thumbnail_url' =>
                'https://picsum.photos/400/250?random=' .
                $this->faker->unique()->numberBetween(1,9999),

            'backdrop_url' =>
                'https://picsum.photos/1600/900?random=' .
                $this->faker->unique()->numberBetween(1,9999),

            'maturity_rating' => $this->faker->randomElement(['U','U/A','A']),
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'is_published' => true
        ];
    }
}
