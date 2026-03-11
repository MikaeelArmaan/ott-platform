<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Content;
use App\Models\Episode;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VideoAsset>
 */
class VideoAssetFactory extends Factory
{
    public function definition(): array
    {
        return [

            'content_id' => Content::factory(),

            'episode_id' => null,

            'type' => $this->faker->randomElement([
                'movie',
                'episode',
                'trailer'
            ]),

            'quality' => $this->faker->randomElement([
                '480p',
                '720p',
                '1080p'
            ]),

            'path' => 'https://storage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',

            'duration' => $this->faker->numberBetween(5400, 9000),

            'mime_type' => 'video/mp4',

            'size' => $this->faker->numberBetween(500000000, 2000000000),

            'is_processed' => true,

            'error' => null

        ];
    }
}
