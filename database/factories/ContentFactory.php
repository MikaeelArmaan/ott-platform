<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContentFactory extends Factory
{
    public function definition(): array
    {
        $type  = $this->faker->randomElement(['movie', 'series']);
        $title = $this->faker->sentence(3);

        return [
            'type' => $type,

            'title' => $title,

            'slug' => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(100, 999),

            'original_title' => null,

            'description' => $this->faker->paragraph(),

            'short_description' => $this->faker->sentence(),

            'language' => $this->faker->randomElement([
                'English',
                'Hindi',
                'Spanish',
                'Japanese'
            ]),

            'country' => $this->faker->randomElement([
                'US',
                'IN',
                'JP',
                'ES'
            ]),

            'maturity_rating' => $this->faker->randomElement([
                'U',
                'UA',
                '16+',
                '18+'
            ]),

            'release_year' => $this->faker->year(),

            'release_date' => $this->faker->date(),

            // movies have duration, series usually handled per episode
            'duration' => $type === 'movie'
                ? $this->faker->numberBetween(5400, 9000)
                : null,

            'poster_url' => 'https://picsum.photos/400/600?random=' . $this->faker->numberBetween(1, 1000),

            'thumbnail_url' => 'https://picsum.photos/800/450?random=' . $this->faker->numberBetween(1, 1000),

            'backdrop_url' => 'https://picsum.photos/1920/1080?random=' . $this->faker->numberBetween(1, 1000),

            'logo_url' => null,

            'imdb_rating' => $this->faker->randomFloat(1, 5, 9),

            'avg_rating' => $this->faker->randomFloat(1, 5, 9),

            'is_featured' => $this->faker->boolean(20),

            'is_trending' => $this->faker->boolean(30),

            'is_published' => true,

            'user_id' => User::inRandomOrder()->value('id'),

            'published_at' => now(),
        ];
    }
}
