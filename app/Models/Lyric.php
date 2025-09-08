<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lyric extends Model
{
    use HasFactory;

    protected $table = 'lyrics';

    protected $fillable = [
        'track_id', 'language', 'version_label', 'is_original', 'is_ai_assisted',
        'content', 'annotations',
    ];

    protected $casts = [
        'is_original' => 'boolean',
        'is_ai_assisted' => 'boolean',
        'annotations' => 'array',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    /** Scope para full-text (si tu DB lo soporta) */
    public function scopeSearch($query, string $term)
    {
        $connection = $this->getConnectionName() ?: config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        if (in_array($driver, ['mysql', 'mariadb'])) {
            return $query->whereFullText('content', $term);
        }

        return $query->where('content', 'like', '%'.$term.'%');
    }
}
