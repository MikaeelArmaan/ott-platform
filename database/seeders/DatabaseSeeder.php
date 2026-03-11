<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;
use App\Models\Season;
use App\Models\Episode;
use App\Models\VideoAsset;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RbacSeeder::class,
            UserSeeder::class,
            ProfileSeeder::class,
        ]);

        Content::factory(12)->create()->each(function ($content) {

            // SERIES CONTENT
            if ($content->type === 'series') {

                $seasonCount = rand(1, 3);

                for ($s = 1; $s <= $seasonCount; $s++) {

                    $season = Season::factory()->create([
                        'content_id' => $content->id,
                        'season_number' => $s,
                        'title' => "Season $s"
                    ]);

                    $episodeCount = rand(5, 10);

                    for ($e = 1; $e <= $episodeCount; $e++) {

                        $episode = Episode::factory()->create([
                            'content_id' => $content->id,
                            'season_id' => $season->id,
                            'episode_number' => $e
                        ]);

                        VideoAsset::factory()->create([
                            'content_id' => $content->id,
                            'episode_id' => $episode->id,
                            'type' => 'episode'
                        ]);
                    }
                }

            }
            // MOVIE CONTENT
            else {

                VideoAsset::factory()->create([
                    'content_id' => $content->id,
                    'episode_id' => null,
                    'type' => 'movie'
                ]);

            }

        });
    }
}