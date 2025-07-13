<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamLogsTable extends Migration
{
    public function up()
    {
        Schema::create('team_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_type_id')->constrained('game_playeds')->onDelete('cascade');
            $table->foreignId('casino_id')->constrained('casinos')->onDelete('cascade');
            $table->json('team_members');
            $table->dateTime('date_time');
            $table->decimal('amount', 10, 2);
            $table->decimal('investment_amount', 10, 2);
            $table->decimal('repayment', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->text('note')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_logs');
    }
}
