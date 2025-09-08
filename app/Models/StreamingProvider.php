<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StreamingProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'base_url',
    ];

    public function trackStreamings()
    {
        return $this->hasMany(TrackStreaming::class);
    }
}
