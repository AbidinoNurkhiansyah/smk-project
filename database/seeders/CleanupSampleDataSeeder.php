<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanupSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data sample quiz yang dibuat oleh created_by = 1
        $quizIds = DB::table('teacher_quizzes')
            ->where('created_by', 1)
            ->pluck('id');

        if ($quizIds->isNotEmpty()) {
            // Hapus options
            $questionIds = DB::table('teacher_quiz_questions')
                ->whereIn('quiz_id', $quizIds)
                ->pluck('id');

            if ($questionIds->isNotEmpty()) {
                DB::table('teacher_quiz_options')
                    ->whereIn('question_id', $questionIds)
                    ->delete();
            }

            // Hapus questions
            DB::table('teacher_quiz_questions')
                ->whereIn('quiz_id', $quizIds)
                ->delete();

            // Hapus quizzes
            DB::table('teacher_quizzes')
                ->whereIn('id', $quizIds)
                ->delete();

            $this->command->info('Sample quiz data deleted successfully!');
        } else {
            $this->command->info('No sample data found to delete.');
        }
    }
}
