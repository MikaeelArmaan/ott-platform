<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            ['name' => 'Action', 'description' => 'High energy movies with stunts and fights'],
            ['name' => 'Adventure', 'description' => 'Exploration and epic journeys'],
            ['name' => 'Comedy', 'description' => 'Light-hearted and humorous content'],
            ['name' => 'Drama', 'description' => 'Serious storytelling with emotional depth'],
            ['name' => 'Horror', 'description' => 'Scary and suspenseful content'],
            ['name' => 'Thriller', 'description' => 'Intense and gripping plots'],
            ['name' => 'Romance', 'description' => 'Love and relationship focused stories'],
            ['name' => 'Sci-Fi', 'description' => 'Science fiction and futuristic themes'],
            ['name' => 'Fantasy', 'description' => 'Magical and supernatural worlds'],
            ['name' => 'Mystery', 'description' => 'Crime solving and investigation'],
            ['name' => 'Animation', 'description' => 'Animated content for all ages'],
            ['name' => 'Documentary', 'description' => 'Real-life stories and facts'],
            ['name' => 'Crime', 'description' => 'Crime-based storytelling'],
            ['name' => 'Family', 'description' => 'Suitable for all age groups'],
            ['name' => 'Biography', 'description' => 'Based on real people'],
            ['name' => 'History', 'description' => 'Historical events and stories'],
            ['name' => 'War', 'description' => 'War and military stories'],
            ['name' => 'Music', 'description' => 'Music-based content'],
            ['name' => 'Sport', 'description' => 'Sports-related content'],
            ['name' => 'Reality', 'description' => 'Reality-based shows'],
        ];

        foreach ($genres as $genre) {
            DB::table('genres')->updateOrInsert(
                ['name' => $genre['name']],
                [
                    'slug' => Str::slug($genre['name']),
                    'description' => $genre['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}