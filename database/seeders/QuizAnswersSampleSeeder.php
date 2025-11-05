<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizAnswersSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user and first quiz
        $firstUser = DB::table('users')->first();
        $firstQuiz = DB::table('teacher_quizzes')->first();
        
        if (!$firstUser || !$firstQuiz) {
            $this->command->info('No users or quizzes found. Please run user and quiz seeders first.');
            return;
        }

        // Get questions for the first quiz
        $questions = DB::table('teacher_quiz_questions')
            ->where('quiz_id', $firstQuiz->id)
            ->get();

        if ($questions->isEmpty()) {
            $this->command->info('No questions found for quiz. Please create quiz with questions first.');
            return;
        }

        // Create sample answers for each question
        foreach ($questions as $question) {
            // Generate random answer (A, B, C, D, E)
            $possibleAnswers = ['A', 'B', 'C', 'D', 'E'];
            $userAnswer = $possibleAnswers[array_rand($possibleAnswers)];
            
            // Check if answer is correct (simplified - just random for demo)
            $isCorrect = rand(0, 1) == 1;
            
            DB::table('teacher_quiz_answers')->insert([
                'user_id' => $firstUser->user_id,
                'quiz_id' => $firstQuiz->id,
                'question_id' => $question->id,
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
                'answered_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create answers for a second user if available
        $secondUser = DB::table('users')->skip(1)->first();
        if ($secondUser) {
            foreach ($questions as $question) {
                $possibleAnswers = ['A', 'B', 'C', 'D', 'E'];
                $userAnswer = $possibleAnswers[array_rand($possibleAnswers)];
                $isCorrect = rand(0, 1) == 1;
                
                DB::table('teacher_quiz_answers')->insert([
                    'user_id' => $secondUser->user_id,
                    'quiz_id' => $firstQuiz->id,
                    'question_id' => $question->id,
                    'user_answer' => $userAnswer,
                    'is_correct' => $isCorrect,
                    'answered_at' => now()->subHours(2),
                    'created_at' => now()->subHours(2),
                    'updated_at' => now()->subHours(2),
                ]);
            }
        }

        $this->command->info('Sample quiz answers created successfully!');
    }
}
