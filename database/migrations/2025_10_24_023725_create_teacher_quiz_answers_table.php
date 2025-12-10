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
        Schema::create('teacher_quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('question_id');
            $table->string('user_answer', 1); // A, B, C, D, E
            $table->boolean('is_correct');
            $table->timestamp('answered_at');
            $table->timestamps();
            
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('quiz_id')->references('id')->on('teacher_quizzes')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('teacher_quiz_questions')->onDelete('cascade');
            
            $table->index(['user_id', 'quiz_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_quiz_answers');
    }
};