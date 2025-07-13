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
        Schema::create('w2gs_forms', function (Blueprint $table) {
            $table->id();
            $table->time('time');
            $table->date('date');
            $table->string('am_pm');
            $table->string('casino_name');
            $table->decimal('winning_amount', 10, 2);
            $table->decimal('fed_tax', 10, 2);
            $table->decimal('state_tax', 10, 2);
            $table->decimal('local_tax', 10, 2);
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('w2gs_forms');
    }
};
