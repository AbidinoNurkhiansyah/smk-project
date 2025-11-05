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
        Schema::create('teacher_quiz_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id'); // foreign key ke teacher_quiz_questions
            $table->string('option_label'); // A, B, C, D, E
            $table->text('option_text');
            $table->timestamps();
            
            $table->foreign('question_id')->references('id')->on('teacher_quiz_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_quiz_options');
    }
};