<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherQuizSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user ID
        $firstUser = DB::table('users')->first();
        $createdBy = $firstUser ? $firstUser->user_id : 998;
        
        // Create sample teacher quiz
        $quizId = DB::table('teacher_quizzes')->insertGetId([
            'class_id' => 1,
            'quiz_title' => 'Quiz Teknik Sepeda Motor - Kelas 10',
            'quiz_description' => 'Quiz untuk menguji pemahaman siswa tentang teknik sepeda motor',
            'difficulty' => 'mudah',
            'total_questions' => 3,
            'time_limit' => 10,
            'points_per_question' => 10,
            'is_active' => 1,
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
                'question' => 'Apa yang terjadi jika oli mesin tidak diganti secara teratur?',
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
            $options = [
                [
                    'question_id' => $questionId,
                    'option_label' => 'A',
                    'option_text' => 'Mendinginkan mesin',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'question_id' => $questionId,
                    'option_label' => 'B',
                    'option_text' => 'Mengatur campuran bahan bakar',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'question_id' => $questionId,
                    'option_label' => 'C',
                    'option_text' => 'Mesin akan rusak',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'question_id' => $questionId,
                    'option_label' => 'D',
                    'option_text' => 'Semua jawaban benar',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            DB::table('teacher_quiz_options')->insert($options);
        }

        $this->command->info('Sample teacher quiz created successfully!');
    }
}
