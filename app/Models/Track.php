<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\Sluggable;

class Track extends Model
{
    use HasFactory, Sluggable;

    protected $slugFrom = 'title';

    protected $fillable = [
        'artist_id', 'album_id',
        'title', 'slug',
        'isrc', 'duration_seconds', 'release_date', 'explicit',
        'language', 'bpm', 'musical_key',
        'spotify_id', 'youtube_video_id', 'apple_music_id', 'amazon_music_id',
        'cover_url', 'external_meta',
    ];

    protected $casts = [
        'release_date' => 'date',
        'explicit' => 'boolean',
        'bpm' => 'integer',
        'duration_seconds' => 'integer',
        'external_meta' => 'array',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function streamings()
    {
        return $this->hasMany(TrackStreaming::class);
    }

    public function lyrics()
    {
        return $this->hasMany(Lyric::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /** Accesor útil: url principal de Spotify si existe */
    public function getSpotifyUrlAttribute(): ?string
    {
        $provider = $this->streamings->firstWhere('provider.slug', 'spotify');
        if ($provider) return $provider->url;

        // fallback by id direct
        return $this->spotify_id ? 'https://open.spotify.com/track/'.$this->spotify_id : null;
    }
}
