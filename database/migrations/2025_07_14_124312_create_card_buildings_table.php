<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('card_buildings', function (Blueprint $table) {
            $table->id();
            $table->time('time');
            $table->string('am_pm');
            $table->date('date');
            $table->foreignId('casino_id')->constrained()->onDelete('cascade');
            $table->string('card_name');
            $table->decimal('cash_in', 10, 2)->nullable();
            $table->decimal('cash_out', 10, 2)->nullable();
            $table->decimal('balance', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('card_buildings');
    }
};
