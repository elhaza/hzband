<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\Sluggable;

class Artist extends Model
{
    use HasFactory, Sluggable;

    protected $slugFrom = 'name';

    protected $fillable = [
        'name', 'slug',
        'spotify_id', 'apple_music_id', 'youtube_channel_id', 'amazon_music_id',
        'country_code', 'external_meta',
    ];

    protected $casts = [
        'external_meta' => 'array',
    ];

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
