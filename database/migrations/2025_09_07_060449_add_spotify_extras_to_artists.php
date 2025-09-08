<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('artists', function (Blueprint $table) {
            if (!Schema::hasColumn('artists', 'genres')) {
                $table->json('genres')->nullable()->after('country_code');
            }
            if (!Schema::hasColumn('artists', 'popularity')) {
                $table->unsignedInteger('popularity')->nullable()->after('genres');
            }
            if (!Schema::hasColumn('artists', 'images')) {
                $table->json('images')->nullable()->after('popularity'); // [{url,width,height},...]
            }
        });
    }

    public function down(): void {
        Schema::table('artists', function (Blueprint $table) {
            foreach (['images','popularity','genres'] as $c) {
                if (Schema::hasColumn('artists', $c)) $table->dropColumn($c);
            }
        });
    }
};
