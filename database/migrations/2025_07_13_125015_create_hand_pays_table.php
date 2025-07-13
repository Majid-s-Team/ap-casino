<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandPaysTable extends Migration
{
    public function up()
    {
        Schema::create('hand_pays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->decimal('handpay_amount', 10, 2);
            $table->decimal('payout', 10, 2);
            $table->decimal('deduction', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hand_pays');
    }
}
