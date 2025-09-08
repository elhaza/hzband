<?php
namespace App\Jobs;

use App\services\SpotifyClient;
use App\services\SpotifyImporter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportAlbumJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(public string $albumId) {}

    public function handle(SpotifyClient $client, SpotifyImporter $importer)
    {
        $albumData = $client->getAlbum($this->albumId);
        $album = $importer->upsertAlbum($albumData);

        foreach ($client->getAlbumTracks($this->albumId) as $track) {
            $importer->upsertTrack($track, $album);
        }
    }
}
