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
        Schema::create('track_streamings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->constrained()->cascadeOnDelete();
            $table->foreignId('streaming_provider_id')->constrained()->cascadeOnDelete();

            $table->string('external_id')->nullable(); // e.g., Spotify track id, YouTube video id
            $table->string('url')->nullable();         // link público
            $table->string('region', 2)->nullable();   // si aplica: MX, US, etc.

            $table->json('external_meta')->nullable(); // e.g., markets, preview_url, etc.
            $table->timestamps();

            $table->unique(['track_id', 'streaming_provider_id'], 'track_provider_unique');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_streamings');
    }
};
