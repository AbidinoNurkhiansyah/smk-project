<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing videos with class_id and add new ones
        $videos = [
            [
                'video_id' => 1,
                'class_id' => 1,
                'judul' => 'Dasar-dasar Mesin TSM X',
                'video_url' => 'https://example.com/video1',
                'description' => 'Materi dasar tentang mesin untuk siswa TSM X',
                'duration' => 30
            ],
            [
                'video_id' => 2,
                'class_id' => 1,
                'judul' => 'Pemeliharaan Mesin Dasar TSM X',
                'video_url' => 'https://example.com/video2',
                'description' => 'Cara melakukan pemeliharaan mesin dasar',
                'duration' => 45
            ],
            [
                'video_id' => 3,
                'class_id' => 1,
                'judul' => 'Sistem Kelistrikan Dasar TSM X',
                'video_url' => 'https://example.com/video3',
                'description' => 'Pengenalan sistem kelistrikan pada kendaraan',
                'duration' => 35
            ],
            [
                'video_id' => 4,
                'class_id' => 2,
                'judul' => 'Sistem Transmisi TSM XI',
                'video_url' => 'https://example.com/video4',
                'description' => 'Materi sistem transmisi untuk siswa TSM XI',
                'duration' => 50
            ],
            [
                'video_id' => 5,
                'class_id' => 2,
                'judul' => 'Diagnosis Mesin TSM XI',
                'video_url' => 'https://example.com/video5',
                'description' => 'Teknik diagnosis masalah pada mesin',
                'duration' => 40
            ],
            [
                'video_id' => 6,
                'class_id' => 2,
                'judul' => 'Sistem Pendingin TSM XI',
                'video_url' => 'https://example.com/video6',
                'description' => 'Sistem pendingin mesin dan komponennya',
                'duration' => 25
            ],
            [
                'video_id' => 7,
                'class_id' => 3,
                'judul' => 'Sistem EFI Lanjutan TSM XII',
                'video_url' => 'https://example.com/video7',
                'description' => 'Sistem Electronic Fuel Injection tingkat lanjut',
                'duration' => 60
            ],
            [
                'video_id' => 8,
                'class_id' => 3,
                'judul' => 'Teknologi Hybrid TSM XII',
                'video_url' => 'https://example.com/video8',
                'description' => 'Pengenalan teknologi kendaraan hybrid',
                'duration' => 55
            ],
            [
                'video_id' => 9,
                'class_id' => 3,
                'judul' => 'Manajemen Bengkel TSM XII',
                'video_url' => 'https://example.com/video9',
                'description' => 'Manajemen dan administrasi bengkel',
                'duration' => 45
            ]
        ];

        foreach ($videos as $video) {
            DB::table('videos')->updateOrInsert(
                ['video_id' => $video['video_id']],
                $video
            );
        }
    }
}