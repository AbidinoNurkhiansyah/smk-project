<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherQuizSeeder extends Seeder
{
    public function run()
    {
        // Create quiz for class 1
        $quizId = DB::table('teacher_quizzes')->insertGetId([
            'class_id' => 1,
            'quiz_title' => 'Quiz Dasar Teknik Sepeda Motor',
            'quiz_description' => 'Quiz untuk menguji pemahaman dasar teknik sepeda motor kelas 10 TSM',
            'total_questions' => 5,
            'time_limit' => 15,
            'points_per_question' => 20,
            'difficulty' => 'mudah',
            'is_active' => true,
            'created_by' => 1000,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Question 1
        $question1Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Apa yang dimaksud dengan sistem pendingin pada sepeda motor?',
            'correct_answer' => 'A',
            'points' => 20,
            'order_number' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('teacher_quiz_options')->insert([
            ['question_id' => $question1Id, 'option_label' => 'A', 'option_text' => 'Sistem untuk mendinginkan mesin', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question1Id, 'option_label' => 'B', 'option_text' => 'Sistem untuk memanaskan mesin', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question1Id, 'option_label' => 'C', 'option_text' => 'Sistem untuk mengisi bahan bakar', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question1Id, 'option_label' => 'D', 'option_text' => 'Sistem untuk mengatur kecepatan', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // Question 2
        $question2Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Komponen apa yang berfungsi untuk menyalakan mesin sepeda motor?',
            'correct_answer' => 'B',
            'points' => 20,
            'order_number' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('teacher_quiz_options')->insert([
            ['question_id' => $question2Id, 'option_label' => 'A', 'option_text' => 'Karburator', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question2Id, 'option_label' => 'B', 'option_text' => 'Starter motor', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question2Id, 'option_label' => 'C', 'option_text' => 'Radiator', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question2Id, 'option_label' => 'D', 'option_text' => 'Transmisi', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // Question 3
        $question3Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Apa fungsi dari karburator pada sepeda motor?',
            'correct_answer' => 'C',
            'points' => 20,
            'order_number' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('teacher_quiz_options')->insert([
            ['question_id' => $question3Id, 'option_label' => 'A', 'option_text' => 'Mendinginkan mesin', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question3Id, 'option_label' => 'B', 'option_text' => 'Mengatur kecepatan', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question3Id, 'option_label' => 'C', 'option_text' => 'Mencampur udara dan bahan bakar', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question3Id, 'option_label' => 'D', 'option_text' => 'Mengubah energi listrik', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // Question 4
        $question4Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Sistem apa yang berfungsi untuk menghentikan sepeda motor?',
            'correct_answer' => 'A',
            'points' => 20,
            'order_number' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('teacher_quiz_options')->insert([
            ['question_id' => $question4Id, 'option_label' => 'A', 'option_text' => 'Sistem rem', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question4Id, 'option_label' => 'B', 'option_text' => 'Sistem pendingin', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question4Id, 'option_label' => 'C', 'option_text' => 'Sistem bahan bakar', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question4Id, 'option_label' => 'D', 'option_text' => 'Sistem listrik', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // Question 5
        $question5Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Apa yang dimaksud dengan TSM?',
            'correct_answer' => 'D',
            'points' => 20,
            'order_number' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('teacher_quiz_options')->insert([
            ['question_id' => $question5Id, 'option_label' => 'A', 'option_text' => 'Teknik Sepeda Motor', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question5Id, 'option_label' => 'B', 'option_text' => 'Teknik Sistem Mesin', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question5Id, 'option_label' => 'C', 'option_text' => 'Teknik Service Motor', 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $question5Id, 'option_label' => 'D', 'option_text' => 'Teknik Sepeda Motor', 'created_at' => now(), 'updated_at' => now()]
        ]);

        $this->command->info('Teacher Quiz seeded successfully!');
    }
}
