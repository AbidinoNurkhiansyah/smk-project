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
        Schema::create('challenges', function (Blueprint $table) {
            $table->integer('challenge_id')->primary();
            $table->integer('object_id')->nullable();
            $table->string('question');
            $table->char('correct_answer', 1);
            $table->integer('point')->default(20);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('object_id')->references('object_id')->on('objects')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
