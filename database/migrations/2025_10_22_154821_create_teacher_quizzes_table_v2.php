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
        Schema::create('teacher_quizzes', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id');
            $table->string('quiz_title');
            $table->text('quiz_description')->nullable();
            $table->integer('total_questions');
            $table->integer('time_limit'); // dalam menit
            $table->integer('points_per_question')->default(10);
            $table->string('difficulty')->default('mudah');
            $table->boolean('is_active')->default(true);
            $table->integer('created_by'); // user_id yang membuat
            $table->timestamps();
            
            $table->foreign('class_id')->references('class_id')->on('classes');
            $table->foreign('created_by')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_quizzes');
    }
};