<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('album_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();

            // Identificadores y metadatos
            $table->string('isrc', 15)->nullable()->unique();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->date('release_date')->nullable();
            $table->boolean('explicit')->default(false);
            $table->string('language', 10)->nullable(); // ISO 639-1/2 (e.g., 'es', 'en')
            $table->unsignedSmallInteger('bpm')->nullable();
            $table->string('musical_key', 8)->nullable(); // e.g., 'C#m', 'G'

            // IDs directos (además de la tabla de streamings)
            $table->string('spotify_id')->nullable()->unique();
            $table->string('youtube_video_id')->nullable();
            $table->string('apple_music_id')->nullable();
            $table->string('amazon_music_id')->nullable();

            $table->string('cover_url')->nullable(); // specific cover of track
            $table->json('external_meta')->nullable(); // payload extra
            $table->timestamps();

            $table->index(['artist_id', 'album_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
