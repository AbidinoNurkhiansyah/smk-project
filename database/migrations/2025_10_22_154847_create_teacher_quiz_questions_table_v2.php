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
        Schema::create('teacher_quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id'); // foreign key ke teacher_quizzes
            $table->text('question');
            $table->string('correct_answer'); // A, B, C, D, E
            $table->integer('points')->default(10);
            $table->integer('order_number'); // urutan soal
            $table->timestamps();
            
            $table->foreign('quiz_id')->references('id')->on('teacher_quizzes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_quiz_questions');
    }
};