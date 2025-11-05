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
        Schema::create('quiz_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id');
            $table->string('quiz_title')->default('Kuis Teknik Sepeda Motor');
            $table->text('quiz_description')->nullable();
            $table->integer('total_questions')->default(15);
            $table->integer('time_limit')->default(20); // dalam menit
            $table->integer('points_per_question')->default(10);
            $table->string('difficulty')->default('mudah');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('class_id')->references('class_id')->on('classes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_settings');
    }
};
