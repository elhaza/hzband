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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('spotify_id')->nullable()->unique();
            $table->string('apple_music_id')->nullable();
            $table->string('youtube_channel_id')->nullable();
            $table->string('amazon_music_id')->nullable();
            $table->string('country_code', 2)->nullable(); // ISO-3166-1 alpha-2
            $table->json('external_meta')->nullable(); // for extra payloads APIs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
