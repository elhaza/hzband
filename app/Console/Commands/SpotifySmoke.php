<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\services\SpotifyClient;
use App\services\SpotifyImporter;

class SpotifySmoke extends Command
{
    protected $signature = 'spotify:smoke {artistId}';
    protected $description = 'Prueba directa de cliente+import, sin colas.';

    public function handle(SpotifyClient $client, SpotifyImporter $importer): int
    {
        $id = $this->argument('artistId');

        $artistData = $client->getArtist($id);
        $artist     = $importer->upsertArtist($artistData);
        $this->info("Artist OK: {$artist->name} ({$artist->spotify_id})");

        $countAlbums = 0; $countTracks = 0;

        foreach ($client->getArtistAlbums($id) as $al) {
            $album = $importer->upsertAlbum($al, $artist); // <- pásale el Artist
            $countAlbums++;

            foreach ($client->getAlbumTracks($al['id']) as $tr) {
                $importer->upsertTrack($tr, $album);
                $countTracks++;
            }
        }

        $this->info("Imported {$countAlbums} albums, {$countTracks} tracks.");
        return self::SUCCESS;
    }
}
