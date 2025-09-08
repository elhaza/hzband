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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('spotify_id')->nullable()->unique();
            $table->string('apple_music_id')->nullable();
            $table->string('amazon_music_id')->nullable();
            $table->date('release_date')->nullable();
            $table->string('upc')->nullable()->unique(); // Album code if it exists
            $table->string('cover_url')->nullable();
            $table->json('external_meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
