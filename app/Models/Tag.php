<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\Sluggable;

class Tag extends Model
{
    use HasFactory, Sluggable;

    protected $slugFrom = 'name';

    protected $fillable = [
        'name', 'slug',
    ];

    public function tracks()
    {
        return $this->belongsToMany(Track::class)->withTimestamps();
    }
}
