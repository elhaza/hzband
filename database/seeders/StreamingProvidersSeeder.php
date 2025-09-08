<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StreamingProvider;

class StreamingProvidersSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            ['name' => 'Spotify',      'slug' => 'spotify',      'base_url' => 'https://open.spotify.com/'],
            ['name' => 'YouTube',      'slug' => 'youtube',      'base_url' => 'https://www.youtube.com/'],
            ['name' => 'Apple Music',  'slug' => 'apple_music',  'base_url' => 'https://music.apple.com/'],
            ['name' => 'Amazon Music', 'slug' => 'amazon_music', 'base_url' => 'https://music.amazon.com/'],
        ];

        foreach ($providers as $p) {
            StreamingProvider::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
