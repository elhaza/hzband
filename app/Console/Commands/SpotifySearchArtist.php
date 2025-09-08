<?php

namespace App\Console\Commands;

use App\Jobs\ImportArtistJob;
use App\services\SpotifyClient;
use Illuminate\Console\Command;

class SpotifySearchArtist extends Command
{
    protected $signature = 'spotify:search-artist {name : Nombre del artista a buscar}';
    protected $description = 'Busca artista por nombre y dispara importación';

    public function handle(SpotifyClient $client): int
    {
        $name = $this->argument('name');
        $items = $client->searchArtist($name);

        if (!$items) {
            $this->warn('No se encontraron artistas.');
            return self::SUCCESS;
        }

        foreach ($items as $i => $a) {
            $this->line(sprintf(
                "[%d] %s | %s | id:%s",
                $i, $a['name'], implode(', ', $a['genres'] ?? []), $a['id']
            ));
        }

        $choice = $this->ask('Selecciona índice (0..n) o escribe "q" para salir');
        if ($choice === 'q') return self::SUCCESS;

        $idx = (int) $choice;
        if (!isset($items[$idx])) {
            $this->error('Índice inválido');
            return self::INVALID;
        }

        $id = $items[$idx]['id'];
        ImportArtistJob::dispatch($id);
        $this->info("Dispatch ImportArtistJob: {$id}");
        return self::SUCCESS;
    }
}
