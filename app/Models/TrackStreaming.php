<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrackStreaming extends Model
{
    use HasFactory;

    protected $table = 'track_streamings';

    protected $fillable = [
        'track_id', 'streaming_provider_id',
        'external_id', 'url', 'region', 'external_meta',
    ];

    protected $casts = [
        'external_meta' => 'array',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function provider()
    {
        return $this->belongsTo(StreamingProvider::class, 'streaming_provider_id');
    }
}
