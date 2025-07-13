<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::table('expenses', function (Blueprint $table) {
        $table->unsignedBigInteger('expense_category_id')->after('id');
        $table->foreign('expense_category_id')->references('id')->on('expense_categories')->onDelete('cascade');
        $table->dropColumn('expense_category');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
