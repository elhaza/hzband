<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('tracks', function (Blueprint $table) {
            if (!Schema::hasColumn('tracks', 'disc_number')) {
                $table->unsignedInteger('disc_number')->default(1)->after('slug');
            }
            if (!Schema::hasColumn('tracks', 'track_number')) {
                $table->unsignedInteger('track_number')->default(1)->after('disc_number');
            }
            if (!Schema::hasColumn('tracks', 'duration_ms')) {
                $table->unsignedBigInteger('duration_ms')->nullable()->after('duration_seconds');
            }
            if (!Schema::hasColumn('tracks', 'popularity')) {
                $table->unsignedInteger('popularity')->nullable()->after('explicit');
            }
            if (!Schema::hasColumn('tracks', 'available_markets')) {
                $table->json('available_markets')->nullable()->after('popularity');
            }
            if (!Schema::hasColumn('tracks', 'preview_url')) {
                $table->string('preview_url')->nullable()->after('amazon_music_id');
            }
            if (!Schema::hasColumn('tracks', 'external_urls')) {
                $table->json('external_urls')->nullable()->after('preview_url'); // {"spotify": "...", ...}
            }

            // Índice útil para orden natural dentro del álbum
            try {
                $table->index(['album_id', 'track_number']);
            } catch (\Throwable $e) {
                // Si ya existía, ignorar
            }
        });
    }

    public function down(): void {
        Schema::table('tracks', function (Blueprint $table) {
            foreach (['external_urls','preview_url','available_markets','popularity','duration_ms','track_number','disc_number'] as $c) {
                if (Schema::hasColumn('tracks', $c)) $table->dropColumn($c);
            }
        });
    }
};
