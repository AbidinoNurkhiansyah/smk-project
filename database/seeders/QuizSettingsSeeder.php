<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'class_id' => 1, // TSM X
                'quiz_title' => 'Kuis Teknik Sepeda Motor - Kelas X',
                'quiz_description' => 'Jawab soal-soal pilihan ganda dengan benar untuk mendapatkan poin!',
                'total_questions' => 20,
                'time_limit' => 25,
                'points_per_question' => 10,
                'difficulty' => 'mudah',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => 2, // TSM XI
                'quiz_title' => 'Kuis Teknik Sepeda Motor - Kelas XI',
                'quiz_description' => 'Jawab soal-soal pilihan ganda dengan benar untuk mendapatkan poin!',
                'total_questions' => 30,
                'time_limit' => 35,
                'points_per_question' => 10,
                'difficulty' => 'sedang',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => 3, // TSM XII
                'quiz_title' => 'Kuis Teknik Sepeda Motor - Kelas XII',
                'quiz_description' => 'Jawab soal-soal pilihan ganda dengan benar untuk mendapatkan poin!',
                'total_questions' => 25,
                'time_limit' => 30,
                'points_per_question' => 10,
                'difficulty' => 'sulit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($settings as $setting) {
            DB::table('quiz_settings')->updateOrInsert(
                ['class_id' => $setting['class_id']],
                $setting
            );
        }
    }
}