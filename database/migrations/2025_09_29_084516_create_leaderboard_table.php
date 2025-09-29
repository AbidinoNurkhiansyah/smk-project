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
        Schema::create('leaderboard', function (Blueprint $table) {
            $table->integer('leaderboard_id')->primary();
            $table->integer('user_id');
            $table->integer('point_id');
            $table->integer('ranking');
            $table->integer('class_id');
            $table->enum('periode', ['weekly','monthly','semester']);

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('point_id')->references('point_id')->on('points')->restrictOnDelete();
            $table->foreign('class_id')->references('class_id')->on('classes')->restrictOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard');
    }
};
