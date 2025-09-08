<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('artist_track')) {
            Schema::create('artist_track', function (Blueprint $table) {
                $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
                $table->foreignId('track_id')->constrained()->cascadeOnDelete();
                $table->primary(['artist_id','track_id']);
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('artist_track');
    }
};
