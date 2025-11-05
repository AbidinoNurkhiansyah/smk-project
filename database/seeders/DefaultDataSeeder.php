<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add default points for user 1 if not exists
        $existingPoints = DB::table('points')->where('user_id', 1)->first();
        if (!$existingPoints) {
            DB::table('points')->insert([
                'user_id' => 1,
                'total_point' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Add default leaderboard entry for user 1 if not exists
        $existingLeaderboard = DB::table('leaderboard')->where('user_id', 1)->first();
        if (!$existingLeaderboard) {
            // Get the point_id from points table
            $pointId = DB::table('points')->where('user_id', 1)->value('point_id');
            if ($pointId) {
                DB::table('leaderboard')->insert([
                    'leaderboard_id' => 1,
                    'user_id' => 1,
                    'point_id' => $pointId,
                    'ranking' => 1,
                    'class_id' => 1,
                    'periode' => 'semester'
                ]);
            }
        }

        // Add some default video progress if not exists
        $videos = DB::table('videos')->get();
        foreach ($videos as $video) {
            $existingProgress = DB::table('video_progress')
                ->where('user_id', 1)
                ->where('video_id', $video->video_id)
                ->first();
                
            if (!$existingProgress) {
                DB::table('video_progress')->insert([
                    'user_id' => 1,
                    'video_id' => $video->video_id,
                    'progress' => 0,
                    'is_completed' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
