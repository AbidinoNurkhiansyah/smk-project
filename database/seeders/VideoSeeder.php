<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample videos
        $videos = [
            [
                'judul' => 'Pengenalan Mesin Motor',
                'description' => 'Pelajari komponen-komponen dasar mesin sepeda motor dan fungsinya dalam sistem kendaraan.',
                'video_url' => 'https://example.com/video1'
            ],
            [
                'judul' => 'Sistem Pendingin Motor',
                'description' => 'Memahami cara kerja sistem pendingin pada sepeda motor dan perawatannya.',
                'video_url' => 'https://example.com/video2'
            ],
            [
                'judul' => 'Sistem Kelistrikan Motor',
                'description' => 'Pelajari sistem kelistrikan kompleks pada sepeda motor modern dan troubleshooting.',
                'video_url' => 'https://example.com/video3'
            ],
            [
                'judul' => 'Perawatan Rutin Motor',
                'description' => 'Jadwal dan prosedur perawatan rutin untuk menjaga performa motor.',
                'video_url' => 'https://example.com/video4'
            ],
            [
                'judul' => 'Troubleshooting Mesin',
                'description' => 'Teknik mendiagnosis dan mengatasi masalah umum pada mesin motor.',
                'video_url' => 'https://example.com/video5'
            ],
            [
                'judul' => 'Modifikasi Motor',
                'description' => 'Panduan aman untuk modifikasi motor dan upgrade performa.',
                'video_url' => 'https://example.com/video6'
            ]
        ];

        DB::table('videos')->insert($videos);

        // Sample video progress for user ID 1
        $videoProgress = [
            ['video_id' => 1, 'user_id' => 1, 'progress' => 100, 'is_completed' => true],
            ['video_id' => 2, 'user_id' => 1, 'progress' => 50,  'is_completed' => false],
            ['video_id' => 3, 'user_id' => 1, 'progress' => 25,  'is_completed' => false],
            ['video_id' => 4, 'user_id' => 1, 'progress' => 50,  'is_completed' => false],
            ['video_id' => 5, 'user_id' => 1, 'progress' => 25,  'is_completed' => false],
            ['video_id' => 6, 'user_id' => 1, 'progress' => 100, 'is_completed' => true],
        ];

        DB::table('video_progress')->insert($videoProgress);

        // Sample points for user ID 1
        DB::table('points')->insert([
            'user_id' => 1,
            'total_point' => 33,
        ]);
    }
}
