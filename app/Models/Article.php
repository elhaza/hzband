<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\Sluggable;

class Article extends Model
{
    use HasFactory, Sluggable;

    protected $slugFrom = 'title';

    protected $fillable = [
        'track_id', 'title', 'slug', 'language', 'summary', 'body',
        'status', 'published_at', 'seo',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'seo' => 'array',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    /** Solo publicados */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }
}
