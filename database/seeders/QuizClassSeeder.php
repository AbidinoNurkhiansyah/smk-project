<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Quiz untuk semua kelas
        $challenges = [
            // TSM X (class_id = 1)
            [
                'challenge_id' => 1,
                'class_id' => 1,
                'object_id' => null,
                'question' => 'Apa fungsi utama dari oli mesin?',
                'correct_answer' => 'A',
                'point' => 20,
                'created_at' => now()
            ],
            [
                'challenge_id' => 2,
                'class_id' => 1,
                'object_id' => null,
                'question' => 'Berapa interval penggantian oli mesin yang disarankan?',
                'correct_answer' => 'B',
                'point' => 20,
                'created_at' => now()
            ],
            [
                'challenge_id' => 3,
                'class_id' => 1,
                'object_id' => null,
                'question' => 'Apa yang dimaksud dengan tune up mesin?',
                'correct_answer' => 'C',
                'point' => 20,
                'created_at' => now()
            ],
            // TSM XI (class_id = 2)
            [
                'challenge_id' => 4,
                'class_id' => 2,
                'object_id' => null,
                'question' => 'Bagaimana cara kerja sistem transmisi manual?',
                'correct_answer' => 'A',
                'point' => 25,
                'created_at' => now()
            ],
            [
                'challenge_id' => 5,
                'class_id' => 2,
                'object_id' => null,
                'question' => 'Apa penyebab umum kerusakan pada sistem transmisi?',
                'correct_answer' => 'B',
                'point' => 25,
                'created_at' => now()
            ],
            [
                'challenge_id' => 6,
                'class_id' => 2,
                'object_id' => null,
                'question' => 'Bagaimana cara mendiagnosis masalah pada sistem pendingin?',
                'correct_answer' => 'C',
                'point' => 25,
                'created_at' => now()
            ],
            // TSM XII (class_id = 3)
            [
                'challenge_id' => 7,
                'class_id' => 3,
                'object_id' => null,
                'question' => 'Apa keunggulan sistem EFI dibanding karburator?',
                'correct_answer' => 'A',
                'point' => 30,
                'created_at' => now()
            ],
            [
                'challenge_id' => 8,
                'class_id' => 3,
                'object_id' => null,
                'question' => 'Bagaimana cara kerja sistem hybrid pada kendaraan?',
                'correct_answer' => 'B',
                'point' => 30,
                'created_at' => now()
            ],
            [
                'challenge_id' => 9,
                'class_id' => 3,
                'object_id' => null,
                'question' => 'Apa yang harus diperhatikan dalam manajemen bengkel?',
                'correct_answer' => 'C',
                'point' => 30,
                'created_at' => now()
            ]
        ];

        foreach ($challenges as $challenge) {
            DB::table('challenges')->updateOrInsert(
                ['challenge_id' => $challenge['challenge_id']],
                $challenge
            );
        }

        // Insert options untuk setiap quiz
        $this->insertQuizOptions();
    }

    private function insertQuizOptions()
    {
        // Options untuk TSM X
        $options = [
            // Quiz 1 - TSM X
            ['option_id' => 1, 'challenge_id' => 1, 'option_label' => 'A', 'option_text' => 'Melumasi komponen mesin'],
            ['option_id' => 2, 'challenge_id' => 1, 'option_label' => 'B', 'option_text' => 'Mendinginkan mesin'],
            ['option_id' => 3, 'challenge_id' => 1, 'option_label' => 'C', 'option_text' => 'Membersihkan mesin'],
            ['option_id' => 4, 'challenge_id' => 1, 'option_label' => 'D', 'option_text' => 'Menambah tenaga mesin'],
            
            // Quiz 2 - TSM X
            ['option_id' => 5, 'challenge_id' => 2, 'option_label' => 'A', 'option_text' => 'Setiap 1000 km'],
            ['option_id' => 6, 'challenge_id' => 2, 'option_label' => 'B', 'option_text' => 'Setiap 5000 km'],
            ['option_id' => 7, 'challenge_id' => 2, 'option_label' => 'C', 'option_text' => 'Setiap 10000 km'],
            ['option_id' => 8, 'challenge_id' => 2, 'option_label' => 'D', 'option_text' => 'Setiap 20000 km'],
            
            // Quiz 3 - TSM X
            ['option_id' => 9, 'challenge_id' => 3, 'option_label' => 'A', 'option_text' => 'Mengganti oli saja'],
            ['option_id' => 10, 'challenge_id' => 3, 'option_label' => 'B', 'option_text' => 'Mencuci mesin'],
            ['option_id' => 11, 'challenge_id' => 3, 'option_label' => 'C', 'option_text' => 'Penyetelan dan perawatan komponen mesin'],
            ['option_id' => 12, 'challenge_id' => 3, 'option_label' => 'D', 'option_text' => 'Mengganti mesin'],
        ];

        foreach ($options as $option) {
            DB::table('challenge_options')->updateOrInsert(
                ['option_id' => $option['option_id']],
                $option
            );
        }

        // Options untuk TSM XI dan XII
        $moreOptions = [
            // Quiz 4 - TSM XI
            ['option_id' => 13, 'challenge_id' => 4, 'option_label' => 'A', 'option_text' => 'Menggunakan kopling dan gigi'],
            ['option_id' => 14, 'challenge_id' => 4, 'option_label' => 'B', 'option_text' => 'Otomatis tanpa kopling'],
            ['option_id' => 15, 'challenge_id' => 4, 'option_label' => 'C', 'option_text' => 'Menggunakan rem tangan'],
            ['option_id' => 16, 'challenge_id' => 4, 'option_label' => 'D', 'option_text' => 'Menggunakan gas saja'],
            
            // Quiz 5 - TSM XI
            ['option_id' => 17, 'challenge_id' => 5, 'option_label' => 'A', 'option_text' => 'Kurang oli'],
            ['option_id' => 18, 'challenge_id' => 5, 'option_label' => 'B', 'option_text' => 'Kurang perawatan dan oli'],
            ['option_id' => 19, 'challenge_id' => 5, 'option_label' => 'C', 'option_text' => 'Terlalu banyak oli'],
            ['option_id' => 20, 'challenge_id' => 5, 'option_label' => 'D', 'option_text' => 'Tidak ada penyebab'],
            
            // Quiz 6 - TSM XI
            ['option_id' => 21, 'challenge_id' => 6, 'option_label' => 'A', 'option_text' => 'Cek suhu mesin'],
            ['option_id' => 22, 'challenge_id' => 6, 'option_label' => 'B', 'option_text' => 'Cek level coolant'],
            ['option_id' => 23, 'challenge_id' => 6, 'option_label' => 'C', 'option_text' => 'Cek suhu, coolant, dan radiator'],
            ['option_id' => 24, 'challenge_id' => 6, 'option_label' => 'D', 'option_text' => 'Cek oli saja'],
            
            // Quiz 7 - TSM XII
            ['option_id' => 25, 'challenge_id' => 7, 'option_label' => 'A', 'option_text' => 'Lebih efisien dan presisi'],
            ['option_id' => 26, 'challenge_id' => 7, 'option_label' => 'B', 'option_text' => 'Lebih murah'],
            ['option_id' => 27, 'challenge_id' => 7, 'option_label' => 'C', 'option_text' => 'Lebih mudah diperbaiki'],
            ['option_id' => 28, 'challenge_id' => 7, 'option_label' => 'D', 'option_text' => 'Tidak ada perbedaan'],
            
            // Quiz 8 - TSM XII
            ['option_id' => 29, 'challenge_id' => 8, 'option_label' => 'A', 'option_text' => 'Hanya menggunakan listrik'],
            ['option_id' => 30, 'challenge_id' => 8, 'option_label' => 'B', 'option_text' => 'Kombinasi mesin dan motor listrik'],
            ['option_id' => 31, 'challenge_id' => 8, 'option_label' => 'C', 'option_text' => 'Hanya menggunakan mesin'],
            ['option_id' => 32, 'challenge_id' => 8, 'option_label' => 'D', 'option_text' => 'Menggunakan tenaga manusia'],
            
            // Quiz 9 - TSM XII
            ['option_id' => 33, 'challenge_id' => 9, 'option_label' => 'A', 'option_text' => 'Hanya keuangan'],
            ['option_id' => 34, 'challenge_id' => 9, 'option_label' => 'B', 'option_text' => 'Hanya inventaris'],
            ['option_id' => 35, 'challenge_id' => 9, 'option_label' => 'C', 'option_text' => 'Keuangan, inventaris, dan SDM'],
            ['option_id' => 36, 'challenge_id' => 9, 'option_label' => 'D', 'option_text' => 'Hanya SDM'],
        ];

        foreach ($moreOptions as $option) {
            DB::table('challenge_options')->updateOrInsert(
                ['option_id' => $option['option_id']],
                $option
            );
        }
    }
}