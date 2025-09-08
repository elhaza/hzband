<?php
namespace App\Console\Commands;

use App\Jobs\ImportArtistJob;
use Illuminate\Console\Command;

class SpotifyImportArtists extends Command
{
    protected $signature = 'spotify:import-artists {ids* : Lista de IDs de artista de Spotify (ej: 1vCWHaC5f2uS3yhpwWbIA6)}';
    protected $description = 'Importa artistas (y sus álbumes y tracks) por IDs de Spotify';

    public function handle(): int
    {
        $ids = $this->argument('ids');
        foreach ($ids as $id) {
            ImportArtistJob::dispatch($id);
            $this->info("Dispatch ImportArtistJob: {$id}");
        }
        return self::SUCCESS;
    }
}
