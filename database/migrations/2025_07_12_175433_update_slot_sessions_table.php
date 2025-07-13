<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('slot_sessions', function (Blueprint $table) {
            $table->dropColumn(['casino_name', 'card_name']); // remove old columns
            $table->foreignId('casino_id')->constrained()->after('am_pm');
            $table->foreignId('game_played_id')->constrained()->after('casino_id');
            $table->decimal('ticket_in', 10, 2)->after('game_played_id');
            $table->decimal('cash_added', 10, 2)->after('ticket_in');
            $table->string('attachment')->nullable()->after('cash_added');
        });
    }

    public function down(): void
    {
        Schema::table('slot_sessions', function (Blueprint $table) {
            $table->dropForeign(['casino_id']);
            $table->dropForeign(['game_played_id']);
            $table->dropColumn([
                'casino_id',
                'game_played_id',
                'ticket_in',
                'cash_added',
                'attachment',
            ]);
            $table->string('casino_name');
            $table->string('card_name');
        });
    }
};
