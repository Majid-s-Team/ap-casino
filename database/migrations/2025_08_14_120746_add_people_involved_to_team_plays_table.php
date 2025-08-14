<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('team_plays', function (Blueprint $table) {
            $table->json('people_involved')->nullable()->after('person_name');
        });
    }

    public function down(): void
    {
        Schema::table('team_plays', function (Blueprint $table) {
            $table->dropColumn('people_involved');
        });
    }
};
