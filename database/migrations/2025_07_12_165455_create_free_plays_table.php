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
        Schema::create('free_plays', function (Blueprint $table) {
            $table->id();
            $table->time('time');
            $table->date('date');
            $table->string('am_pm');
            $table->foreignId('casino_id')->constrained();
            $table->foreignId('game_played_id')->constrained();
            $table->string('person_name');
            $table->decimal('fp_amount', 10, 2);
            $table->decimal('cash_out', 10, 2);
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('free_plays');
    }
};
