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
        Schema::create('streaming_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // 'Spotify', 'YouTube', 'Apple Music', 'Amazon Music'
            $table->string('slug')->unique(); // 'spotify', 'youtube', 'apple_music', 'amazon_music'
            $table->string('base_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('streaming_providers');
    }
};
