<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use App\Models\VideoProgress;
use App\Models\Points;
use App\Models\Leaderboard;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample videos using DB::table to match migration structure
        $videos = [
            [
                'video_id' => 1,
                'judul' => 'Pengenalan Mesin Motor',
                'description' => 'Pelajari komponen-komponen dasar mesin sepeda motor dan fungsinya dalam sistem kendaraan.',
                'video_url' => 'https://example.com/video1'
            ],
            [
                'video_id' => 2,
                'judul' => 'Sistem Pendingin Motor',
                'description' => 'Memahami cara kerja sistem pendingin pada sepeda motor dan perawatannya.',
                'video_url' => 'https://example.com/video2'
            ],
            [
                'video_id' => 3,
                'judul' => 'Sistem Kelistrikan Motor',
                'description' => 'Pelajari sistem kelistrikan kompleks pada sepeda motor modern dan troubleshooting.',
                'video_url' => 'https://example.com/video3'
            ],
            [
                'video_id' => 4,
                'judul' => 'Perawatan Rutin Motor',
                'description' => 'Jadwal dan prosedur perawatan rutin untuk menjaga performa motor.',
                'video_url' => 'https://example.com/video4'
            ],
            [
                'video_id' => 5,
                'judul' => 'Troubleshooting Mesin',
                'description' => 'Teknik mendiagnosis dan mengatasi masalah umum pada mesin motor.',
                'video_url' => 'https://example.com/video5'
            ],
            [
                'video_id' => 6,
                'judul' => 'Modifikasi Motor',
                'description' => 'Panduan aman untuk modifikasi motor dan upgrade performa.',
                'video_url' => 'https://example.com/video6'
            ]
        ];

        foreach ($videos as $videoData) {
            DB::table('videos')->insert($videoData);
        }

        // Create sample video progress for user ID 1
        $videoProgress = [
            ['progress_id' => 1, 'video_id' => 1, 'user_id' => 1, 'progress' => 100, 'is_completed' => true],
            ['progress_id' => 2, 'video_id' => 2, 'user_id' => 1, 'progress' => 50, 'is_completed' => false],
            ['progress_id' => 3, 'video_id' => 3, 'user_id' => 1, 'progress' => 25, 'is_completed' => false],
            ['progress_id' => 4, 'video_id' => 4, 'user_id' => 1, 'progress' => 50, 'is_completed' => false],
            ['progress_id' => 5, 'video_id' => 5, 'user_id' => 1, 'progress' => 25, 'is_completed' => false],
            ['progress_id' => 6, 'video_id' => 6, 'user_id' => 1, 'progress' => 100, 'is_completed' => true]
        ];

        foreach ($videoProgress as $progressData) {
            DB::table('video_progress')->insert($progressData);
        }

        // Create sample points for user ID 1
        DB::table('points')->insert([
            'point_id' => 1,
            'user_id' => 1,
            'total_point' => 33, // Total points
        ]);
    }
}