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
        Schema::create('lyrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->constrained()->cascadeOnDelete();
            $table->string('language', 10)->nullable(); // 'es', 'en'
            $table->string('version_label')->nullable(); // 'original', 'acoustic', 'english-adaptation', etc.
            $table->boolean('is_original')->default(true);
            $table->boolean('is_ai_assisted')->default(false);

            $table->longText('content'); // Lyric can be long
            $table->json('annotations')->nullable(); // Structure for annotations
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lyrics');
    }
};
