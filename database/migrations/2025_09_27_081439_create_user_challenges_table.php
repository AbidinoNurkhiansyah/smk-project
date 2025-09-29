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
        Schema::create('user_challenges', function (Blueprint $table) {
            $table->integer('user_challenges_id')->primary();
            $table->integer('user_id');
            $table->integer('challenge_id');
            $table->integer('option_id');
            $table->boolean('is_correct')->nullable();
            $table->integer('score')->default(0);
            $table->timestamp('answered_at')->useCurrent();

            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('challenge_id')->references('challenge_id')->on('challenges')->restrictOnDelete();
            $table->foreign('option_id')->references('option_id')->on('challenge_options')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_challenges');
    }
};
