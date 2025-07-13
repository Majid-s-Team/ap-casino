<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('slot_sessions', function (Blueprint $table) {
            $table->id();
            $table->time('time');
            $table->date('date');
            $table->string('am_pm');
            $table->string('casino_name');
            $table->string('card_name');
            $table->decimal('cash_in', 10, 2);
            $table->decimal('cash_out', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->decimal('total_points', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_sessions');
    }
};
