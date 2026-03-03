<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        Content::factory()->count(50)->create([
            'type' => 'movie'
        ]);

        Content::factory()->count(30)->create([
            'type' => 'series'
        ]);
    }
}
