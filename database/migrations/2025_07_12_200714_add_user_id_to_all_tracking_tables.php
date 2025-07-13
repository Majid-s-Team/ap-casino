<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToAllTrackingTables extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('free_plays', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('team_plays', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('slot_sessions', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('w2gs_forms', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('game_playeds', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('free_plays', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('team_plays', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('slot_sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('w2gs_forms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('game_playeds', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
}
