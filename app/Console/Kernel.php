<?php

namespace App\Console;

use App\Jobs\ImportAlbumJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\SpotifyImportArtists::class,
        \App\Console\Commands\SpotifySearchArtist::class,
        \App\Console\Commands\SpotifySmoke::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(job: new ImportAlbumJob(config('services.spotify.artist_id')))->daily(); //Algoritmo Flow daily job
    }


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

