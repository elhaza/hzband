<?php
namespace App\Jobs;

use App\Models\Artist;
use App\Services\SpotifyClient;
use App\Services\SpotifyImporter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportArtistJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(public string $artistId) {}

    public function handle(SpotifyClient $client, SpotifyImporter $importer)
    {
        $data = $client->getArtist($this->artistId);
        $artist = $importer->upsertArtist($data);

        foreach ($client->getArtistAlbums($this->artistId) as $album) {
            ImportAlbumJob::dispatch($album['id']);
        }
    }
}
