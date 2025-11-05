<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizKelas10Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 15 soal mudah untuk kelas 10 (TSM X)
        $questions = [
            [
                'challenge_id' => 101,
                'question' => 'Apa fungsi dari karburator pada sepeda motor?',
                'class_id' => 1, // TSM X
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'B',
                'point' => 10
            ],
            [
                'challenge_id' => 102,
                'question' => 'Apa fungsi dari busi pada sepeda motor?',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'B',
                'point' => 10
            ],
            [
                'challenge_id' => 103,
                'question' => 'Komponen berikut yang berfungsi menggerakkan klep adalah...',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'A',
                'point' => 10
            ],
            [
                'challenge_id' => 104,
                'question' => 'Apa fungsi dari piston pada mesin sepeda motor?',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'C',
                'point' => 10
            ],
            [
                'challenge_id' => 105,
                'question' => 'Sistem pendingin pada sepeda motor menggunakan...',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'A',
                'point' => 10
            ],
            [
                'challenge_id' => 106,
                'question' => 'Apa fungsi dari filter udara pada sepeda motor?',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'B',
                'point' => 10
            ],
            [
                'challenge_id' => 107,
                'question' => 'Komponen yang mengatur aliran bahan bakar ke karburator adalah...',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'C',
                'point' => 10
            ],
            [
                'challenge_id' => 108,
                'question' => 'Apa fungsi dari rantai pada sepeda motor?',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'A',
                'point' => 10
            ],
            [
                'challenge_id' => 109,
                'question' => 'Sistem pengereman pada sepeda motor terdiri dari...',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'B',
                'point' => 10
            ],
            [
                'challenge_id' => 110,
                'question' => 'Apa fungsi dari aki pada sepeda motor?',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'C',
                'point' => 10
            ],
            [
                'challenge_id' => 111,
                'question' => 'Komponen yang mengatur putaran mesin adalah...',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'A',
                'point' => 10
            ],
            [
                'challenge_id' => 112,
                'question' => 'Apa fungsi dari knalpot pada sepeda motor?',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'B',
                'point' => 10
            ],
            [
                'challenge_id' => 113,
                'question' => 'Sistem transmisi pada sepeda motor berfungsi untuk...',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'C',
                'point' => 10
            ],
            [
                'challenge_id' => 114,
                'question' => 'Apa fungsi dari oli mesin pada sepeda motor?',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'A',
                'point' => 10
            ],
            [
                'challenge_id' => 115,
                'question' => 'Komponen yang menghubungkan mesin dengan roda belakang adalah...',
                'class_id' => 1,
                'type' => 'quiz',
                'difficulty' => 'mudah',
                'correct_answer' => 'B',
                'point' => 10
            ]
        ];

        // Insert questions
        foreach ($questions as $question) {
            DB::table('challenges')->updateOrInsert(
                ['challenge_id' => $question['challenge_id']],
                $question
            );
        }

        // Options for each question
        $options = [
            // Question 1
            ['option_id' => 1001, 'challenge_id' => 101, 'option_label' => 'A', 'option_text' => 'Mengatur suhu mesin'],
            ['option_id' => 1002, 'challenge_id' => 101, 'option_label' => 'B', 'option_text' => 'Mencampur udara dan bahan bakar'],
            ['option_id' => 1003, 'challenge_id' => 101, 'option_label' => 'C', 'option_text' => 'Mengatur tekanan oli'],
            
            // Question 2
            ['option_id' => 1004, 'challenge_id' => 102, 'option_label' => 'A', 'option_text' => 'Mendinginkan mesin'],
            ['option_id' => 1005, 'challenge_id' => 102, 'option_label' => 'B', 'option_text' => 'Membakar campuran bahan bakar dan udara'],
            ['option_id' => 1006, 'challenge_id' => 102, 'option_label' => 'C', 'option_text' => 'Menyalurkan bahan bakar ke ruang bakar'],
            
            // Question 3
            ['option_id' => 1007, 'challenge_id' => 103, 'option_label' => 'A', 'option_text' => 'Noken as (camshaft)'],
            ['option_id' => 1008, 'challenge_id' => 103, 'option_label' => 'B', 'option_text' => 'Piston'],
            ['option_id' => 1009, 'challenge_id' => 103, 'option_label' => 'C', 'option_text' => 'Karburator'],
            
            // Question 4
            ['option_id' => 1010, 'challenge_id' => 104, 'option_label' => 'A', 'option_text' => 'Mengatur aliran bahan bakar'],
            ['option_id' => 1011, 'challenge_id' => 104, 'option_label' => 'B', 'option_text' => 'Mendinginkan mesin'],
            ['option_id' => 1012, 'challenge_id' => 104, 'option_label' => 'C', 'option_text' => 'Mengubah energi panas menjadi gerak'],
            
            // Question 5
            ['option_id' => 1013, 'challenge_id' => 105, 'option_label' => 'A', 'option_text' => 'Udara'],
            ['option_id' => 1014, 'challenge_id' => 105, 'option_label' => 'B', 'option_text' => 'Air'],
            ['option_id' => 1015, 'challenge_id' => 105, 'option_label' => 'C', 'option_text' => 'Oli'],
            
            // Question 6
            ['option_id' => 1016, 'challenge_id' => 106, 'option_label' => 'A', 'option_text' => 'Mengatur tekanan udara'],
            ['option_id' => 1017, 'challenge_id' => 106, 'option_label' => 'B', 'option_text' => 'Menyaring udara yang masuk ke mesin'],
            ['option_id' => 1018, 'challenge_id' => 106, 'option_label' => 'C', 'option_text' => 'Mengatur aliran bahan bakar'],
            
            // Question 7
            ['option_id' => 1019, 'challenge_id' => 107, 'option_label' => 'A', 'option_text' => 'Filter udara'],
            ['option_id' => 1020, 'challenge_id' => 107, 'option_label' => 'B', 'option_text' => 'Busi'],
            ['option_id' => 1021, 'challenge_id' => 107, 'option_label' => 'C', 'option_text' => 'Kran bensin'],
            
            // Question 8
            ['option_id' => 1022, 'challenge_id' => 108, 'option_label' => 'A', 'option_text' => 'Menyalurkan tenaga dari mesin ke roda'],
            ['option_id' => 1023, 'challenge_id' => 108, 'option_label' => 'B', 'option_text' => 'Mengatur kecepatan'],
            ['option_id' => 1024, 'challenge_id' => 108, 'option_label' => 'C', 'option_text' => 'Mendinginkan mesin'],
            
            // Question 9
            ['option_id' => 1025, 'challenge_id' => 109, 'option_label' => 'A', 'option_text' => 'Rem depan saja'],
            ['option_id' => 1026, 'challenge_id' => 109, 'option_label' => 'B', 'option_text' => 'Rem depan dan belakang'],
            ['option_id' => 1027, 'challenge_id' => 109, 'option_label' => 'C', 'option_text' => 'Rem belakang saja'],
            
            // Question 10
            ['option_id' => 1028, 'challenge_id' => 110, 'option_label' => 'A', 'option_text' => 'Mengatur kecepatan'],
            ['option_id' => 1029, 'challenge_id' => 110, 'option_label' => 'B', 'option_text' => 'Mendinginkan mesin'],
            ['option_id' => 1030, 'challenge_id' => 110, 'option_label' => 'C', 'option_text' => 'Menyimpan energi listrik'],
            
            // Question 11
            ['option_id' => 1031, 'challenge_id' => 111, 'option_label' => 'A', 'option_text' => 'Gas'],
            ['option_id' => 1032, 'challenge_id' => 111, 'option_label' => 'B', 'option_text' => 'Kopling'],
            ['option_id' => 1033, 'challenge_id' => 111, 'option_label' => 'C', 'option_text' => 'Rantai'],
            
            // Question 12
            ['option_id' => 1034, 'challenge_id' => 112, 'option_label' => 'A', 'option_text' => 'Mengatur kecepatan'],
            ['option_id' => 1035, 'challenge_id' => 112, 'option_label' => 'B', 'option_text' => 'Mengeluarkan gas buang'],
            ['option_id' => 1036, 'challenge_id' => 112, 'option_label' => 'C', 'option_text' => 'Mendinginkan mesin'],
            
            // Question 13
            ['option_id' => 1037, 'challenge_id' => 113, 'option_label' => 'A', 'option_text' => 'Mengatur kecepatan'],
            ['option_id' => 1038, 'challenge_id' => 113, 'option_label' => 'B', 'option_text' => 'Mendinginkan mesin'],
            ['option_id' => 1039, 'challenge_id' => 113, 'option_label' => 'C', 'option_text' => 'Mengatur perbandingan gigi'],
            
            // Question 14
            ['option_id' => 1040, 'challenge_id' => 114, 'option_label' => 'A', 'option_text' => 'Melumasi dan mendinginkan mesin'],
            ['option_id' => 1041, 'challenge_id' => 114, 'option_label' => 'B', 'option_text' => 'Mengatur kecepatan'],
            ['option_id' => 1042, 'challenge_id' => 114, 'option_label' => 'C', 'option_text' => 'Mengatur tekanan udara'],
            
            // Question 15
            ['option_id' => 1043, 'challenge_id' => 115, 'option_label' => 'A', 'option_text' => 'Rantai'],
            ['option_id' => 1044, 'challenge_id' => 115, 'option_label' => 'B', 'option_text' => 'Rantai dan sprocket'],
            ['option_id' => 1045, 'challenge_id' => 115, 'option_label' => 'C', 'option_text' => 'Kopling']
        ];

        // Insert options
        foreach ($options as $option) {
            DB::table('challenge_options')->updateOrInsert(
                ['option_id' => $option['option_id']],
                $option
            );
        }
    }
}
