<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\Sluggable;

class Album extends Model
{
    use HasFactory, Sluggable;

    protected $slugFrom = 'title';

    protected $fillable = [
        'artist_id', 'title', 'slug', 'spotify_id', 'apple_music_id',
        'amazon_music_id', 'release_date', 'upc', 'cover_url', 'external_meta',
    ];

    protected $casts = [
        'release_date' => 'date',
        'external_meta' => 'array',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
