<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('albums', function (Blueprint $table) {
            if (!Schema::hasColumn('albums', 'album_type')) {
                $table->string('album_type')->nullable()->after('spotify_id'); // album|single|compilation
            }
            if (!Schema::hasColumn('albums', 'release_date_precision')) {
                $table->string('release_date_precision')->nullable()->after('release_date'); // year|month|day
            }
            if (!Schema::hasColumn('albums', 'release_date_text')) {
                $table->string('release_date_text')->nullable()->after('release_date_precision'); // "2021" o "2021-07"
            }
            if (!Schema::hasColumn('albums', 'label')) {
                $table->string('label')->nullable()->after('release_date_text');
            }
            if (!Schema::hasColumn('albums', 'total_tracks')) {
                $table->unsignedInteger('total_tracks')->nullable()->after('label');
            }
            if (!Schema::hasColumn('albums', 'images')) {
                $table->json('images')->nullable()->after('total_tracks');
            }
            if (!Schema::hasColumn('albums', 'market')) {
                $table->string('market', 4)->nullable()->after('images'); // MX, US, ES...
            }
        });
    }

    public function down(): void {
        Schema::table('albums', function (Blueprint $table) {
            $cols = ['market','images','total_tracks','label','release_date_text','release_date_precision','album_type'];
            foreach ($cols as $c) if (Schema::hasColumn('albums', $c)) $table->dropColumn($c);
        });
    }
};
