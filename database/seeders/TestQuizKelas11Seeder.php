<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestQuizKelas11Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user ID
        $firstUser = DB::table('users')->first();
        $createdBy = $firstUser ? $firstUser->user_id : 998;
        
        // Create test quiz for class 11
        $quizId = DB::table('teacher_quizzes')->insertGetId([
            'class_id' => 2, // Kelas 11
            'quiz_title' => 'Test Quiz Kelas 11 - Teknik Sepeda Motor',
            'quiz_description' => 'Quiz test untuk menguji pemahaman siswa kelas 11 tentang teknik sepeda motor',
            'difficulty' => 'mudah',
            'total_questions' => 3,
            'time_limit' => 15,
            'points_per_question' => 10,
            'is_active' => true,
            'created_by' => $createdBy,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create sample questions
        $questions = [
            [
                'quiz_id' => $quizId,
                'question' => 'Apa fungsi utama dari sistem pendingin pada sepeda motor?',
                'correct_answer' => 'A',
                'points' => 10,
                'order_number' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'quiz_id' => $quizId,
                'question' => 'Komponen apa yang berfungsi untuk mengatur campuran bahan bakar dan udara?',
                'correct_answer' => 'B',
                'points' => 10,
                'order_number' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'quiz_id' => $quizId,
                'question' => 'Berapa kali oli mesin sebaiknya diganti?',
                'correct_answer' => 'C',
                'points' => 10,
                'order_number' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($questions as $question) {
            $questionId = DB::table('teacher_quiz_questions')->insertGetId($question);
            
            // Create options for each question
            if ($question['order_number'] == 1) {
                $options = [
                    ['question_id' => $questionId, 'option_label' => 'A', 'option_text' => 'Mendinginkan mesin', 'created_at' => now(), 'updated_at' => now()],
                    ['question_id' => $questionId, 'option_label' => 'B', 'option_text' => 'Mengatur bahan bakar', 'created_at' => now(), 'updated_at' => now()],
                    ['question_id' => $questionId, 'option_label' => 'C', 'option_text' => 'Menyalakan motor', 'created_at' => now(), 'updated_at' => now()],
                    ['question_id' => $questionId, 'option_label' => 'D', 'option_text' => 'Mengatur kecepatan', 'created_at' => now(), 'updated_at' => now()]
                ];
            } elseif ($question['order_number'] == 2) {
                $options = [
                    ['question_id' => $questionId, 'option_label' => 'A', 'option_text' => 'Karburator', 'created_at' => now(), 'updated_at' => now()],
                    ['question_id' => $questionId, 'option_label' => 'B', 'option_text' => 'Karburator', 'created_at' => now(), 'updated_at' => now()],
                    ['question_id' => $questionId, 'option_label' => 'C', 'option_text' => 'Radiator', 'created_at' => now(), 'updated_at' => now()],
                    ['question_id' => $questionId, 'option_label' => 'D', 'option_text' => 'Alternator', 'created_at' => now(), 'updated_at' => now()]
                ];
            } else {
                $options = [
                    ['question_id' => $questionId, 'option_label' => 'A', 'option_text' => 'Setiap 500 km', 'created_at' => now(), 'updated_at' => now()],
                    ['question_id' => $questionId, 'option_label' => 'B', 'option_text' => 'Setiap 1000 km', 'created_at' => now(), 'updated_at' => now()],
                    ['question_id' => $questionId, 'option_label' => 'C', 'option_text' => 'Setiap 2000 km', 'created_at' => now(), 'updated_at' => now()],
                    ['question_id' => $questionId, 'option_label' => 'D', 'option_text' => 'Setiap 5000 km', 'created_at' => now(), 'updated_at' => now()]
                ];
            }

            DB::table('teacher_quiz_options')->insert($options);
        }

        $this->command->info('Test quiz for class 11 created successfully!');
    }
}
