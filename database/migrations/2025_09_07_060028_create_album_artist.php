<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('album_artist')) {
            Schema::create('album_artist', function (Blueprint $table) {
                $table->foreignId('album_id')->constrained()->cascadeOnDelete();
                $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
                $table->primary(['album_id','artist_id']);
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('album_artist');
    }
};
