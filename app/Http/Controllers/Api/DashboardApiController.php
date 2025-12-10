<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $userData = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.user_id', $user->user_id)
            ->select('users.*', 'classes.class_name')
            ->first();

        // Get videos from database based on user's class
        $videos = DB::table('videos')
            ->where('class_id', $userData->class_id)
            ->get();
        
        // Get video progress for current user
        $videoProgress = DB::table('video_progress')
            ->where('user_id', $user->user_id)
            ->get()
            ->keyBy('video_id');

        // Calculate progress statistics
        $totalVideos = $videos->count();
        $completedVideos = $videoProgress->where('progress', 100)->count();
        $inProgressVideos = $videoProgress->where('progress', '>', 0)
                                       ->where('progress', '<', 100)->count();
        $notStartedVideos = $totalVideos - $completedVideos - $inProgressVideos;

        // Calculate total watch time
        $totalWatchTime = $videos->sum('duration') ?? 0;

        // Calculate overall progress percentage
        $overallProgress = $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100) : 0;

        // Get user points
        $userPoints = DB::table('points')
            ->where('user_id', $user->user_id)
            ->value('total_point') ?? 0;

        // Prepare video data with progress
        $videoData = $videos->map(function($video) use ($videoProgress) {
            $progress = $videoProgress->get($video->video_id);
            $progressPercentage = $progress ? $progress->progress : 0;
            
            return [
                'id' => $video->video_id,
                'title' => $video->judul,
                'description' => $video->description ?? '',
                'category' => 'General',
                'duration' => $this->formatDuration($video->duration ?? 0),
                'duration_minutes' => $video->duration ?? 0,
                'thumbnail' => 'play',
                'progress' => $progressPercentage,
                'is_completed' => $progressPercentage == 100,
                'is_in_progress' => $progressPercentage > 0 && $progressPercentage < 100,
                'video_url' => $video->video_url
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'name' => $userData->user_name,
                    'class' => $userData->class_name,
                    'profile_picture' => $userData->profile_picture ? url('storage/' . $userData->profile_picture) : null,
                ],
                'statistics' => [
                    'total_videos' => $totalVideos,
                    'completed_videos' => $completedVideos,
                    'in_progress_videos' => $inProgressVideos,
                    'not_started_videos' => $notStartedVideos,
                    'total_watch_time' => $totalWatchTime,
                    'overall_progress' => $overallProgress,
                    'user_points' => $userPoints,
                ],
                'videos' => $videoData->values()
            ]
        ]);
    }

    public function getProgress(Request $request)
    {
        $user = $request->user();
        
        $userClassId = $user->class_id;
        $videos = DB::table('videos')
            ->where('class_id', $userClassId)
            ->get();
        $videoProgress = DB::table('video_progress')
            ->where('user_id', $user->user_id)
            ->get()
            ->keyBy('video_id');
        
        $completed = $videoProgress->where('progress', 100)->count();
        $inProgress = $videoProgress->where('progress', '>', 0)
                                  ->where('progress', '<', 100)->count();
        $notStarted = $videos->count() - $completed - $inProgress;
        
        $progressData = [
            'total' => $videos->count(),
            'completed' => $completed,
            'in_progress' => $inProgress,
            'not_started' => $notStarted,
            'overall_percentage' => $videos->count() > 0 ? 
                round(($completed / $videos->count()) * 100) : 0
        ];

        return response()->json([
            'success' => true,
            'data' => $progressData
        ]);
    }

    private function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d', $hours, $mins);
        }
        
        return sprintf('%d:%02d', 0, $mins);
    }
}

