<?php

namespace App\services;

use App\Models\Artist;
use App\Models\Album;
use App\Models\Track;
use Illuminate\Support\Str;

class SpotifyImporter
{
    public function upsertArtist(array $data): Artist
    {
        return Artist::updateOrCreate(
            ['spotify_id' => $data['id']],
            [
                'name'          => $data['name'] ?? 'Unknown',
                'slug'          => Str::slug($data['name'] ?? $data['id']),
                'spotify_id'    => $data['id'],
                'external_meta' => $data,
            ]
        );
    }

    public function upsertAlbum(array $data, ?Artist $artist = null): Album
    {
        $title = $data['name'] ?? 'Unknown';
        $slug  = Str::slug(($data['name'] ?? 'album') . '-' . $data['id']);

        return Album::updateOrCreate(
            ['spotify_id' => $data['id']],
            [
                'artist_id'     => $artist?->id, // si tu esquema lo tiene
                'title'         => $title,       // <- IMPORTANTE: mapear name -> title
                'slug'          => $slug,
                'album_type'    => $data['album_type'] ?? null,
                'release_date'  => $this->normalizeReleaseDate(
                    $data['release_date'] ?? null,
                    $data['release_date_precision'] ?? null
                ),
                'total_tracks'  => $data['total_tracks'] ?? null,
                'cover_url'     => $this->pickCover($data['images'] ?? []),
                'external_meta' => $data,
            ]
        );
    }

    public function upsertTrack(array $data, Album $album): Track
    {
        $title = $data['name'] ?? 'Unknown';
        $slug  = Str::slug(($data['name'] ?? 'track') . '-' . $data['id']);

        return Track::updateOrCreate(
            ['spotify_id' => $data['id']],
            [
                'album_id'      => $album->id,
                'title'         => $title,  // si tu tabla es 'title'; si fuera 'name', cámbialo
                'slug'          => $slug,
                'disc_number'   => $data['disc_number'] ?? null,
                'track_number'  => $data['track_number'] ?? null,
                'duration_ms'   => $data['duration_ms'] ?? null,
                'explicit'      => (bool) ($data['explicit'] ?? false),
                'preview_url'   => $data['preview_url'] ?? null,
                'external_meta' => $data,
            ]
        );
    }

    /** ---- Helpers ---- */

    /**
     * Spotify manda precisiones: year|month|day. Normalizamos a 'Y-m-d'.
     */
    protected function normalizeReleaseDate(?string $date, ?string $precision): ?string
    {
        if (!$date) {
            return null;
        }
        $precision = $precision ?: 'day';

        try {
            return match ($precision) {
                'year'  => $date . '-01-01',
                'month' => $date . '-01',
                default => $date, // ya viene como YYYY-MM-DD
            };
        } catch (\Throwable) {
            return null;
        }
    }

    protected function pickCover(array $images): ?string
    {
        // Spotify envía array de {url,width,height}. Normalmente el 0 es el mayor.
        if (empty($images)) {
            return null;
        }
        // por si acaso, elegimos el de mayor ancho
        usort($images, fn($a, $b) => ($b['width'] ?? 0) <=> ($a['width'] ?? 0));
        return $images[0]['url'] ?? null;
    }
}
