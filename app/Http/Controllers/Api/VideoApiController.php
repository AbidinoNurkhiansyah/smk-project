<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoApiController extends Controller
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

        // Prepare video data with progress
        $videoData = $videos->map(function($video) use ($videoProgress, $userData) {
            $progress = $videoProgress->get($video->video_id);
            $progressPercentage = $progress ? (int)$progress->progress : 0;
            $progressPercentage = max(0, min(100, $progressPercentage));
            
            return [
                'id' => $video->video_id,
                'title' => $video->judul,
                'description' => $video->description ?? 'Video pembelajaran untuk ' . $userData->class_name,
                'duration' => $this->formatDuration($video->duration ?? 0),
                'duration_minutes' => $video->duration ?? 0,
                'progress' => $progressPercentage,
                'is_completed' => $progressPercentage >= 100,
                'is_in_progress' => $progressPercentage > 0 && $progressPercentage < 100,
                'video_url' => $video->video_url
            ];
        });

        // Calculate progress statistics
        $totalVideos = $videos->count();
        $completedVideos = $videoProgress->filter(function($progress) {
            return (int)$progress->progress >= 100;
        })->count();
        $inProgressVideos = $videoProgress->filter(function($progress) {
            $p = (int)$progress->progress;
            return $p > 0 && $p < 100;
        })->count();
        $notStartedVideos = $totalVideos - $completedVideos - $inProgressVideos;
        $overallProgress = $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100) : 0;

        // Get user stats
        $totalPoints = DB::table('points')->where('user_id', $user->user_id)->value('total_point') ?? 0;
        $userRanking = DB::table('users')
            ->join('points', 'users.user_id', '=', 'points.user_id')
            ->where('users.role', 'siswa')
            ->where('users.class_id', $user->class_id)
            ->where('points.total_point', '>', $totalPoints)
            ->count() + 1;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'name' => $userData->user_name,
                    'class' => $userData->class_name,
                    'class_id' => $userData->class_id
                ],
                'statistics' => [
                    'total_videos' => $totalVideos,
                    'completed_videos' => $completedVideos,
                    'in_progress_videos' => $inProgressVideos,
                    'not_started_videos' => $notStartedVideos,
                    'overall_progress' => $overallProgress,
                    'total_points' => $totalPoints,
                    'user_ranking' => $userRanking,
                ],
                'videos' => $videoData->values()
            ]
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $video = DB::table('videos')
            ->join('classes', 'videos.class_id', '=', 'classes.class_id')
            ->where('videos.video_id', $id)
            ->select('videos.*', 'classes.class_name')
            ->first();

        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'Video tidak ditemukan.'
            ], 404);
        }

        // Get video progress for current user
        $progress = DB::table('video_progress')
            ->where('user_id', $user->user_id)
            ->where('video_id', $id)
            ->first();

        $progressPercentage = $progress ? (int)$progress->progress : 0;
        $progressPercentage = max(0, min(100, $progressPercentage));

        // Convert video URL to embed format
        $embedUrl = $this->convertToEmbedUrl($video->video_url);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $video->video_id,
                'title' => $video->judul,
                'description' => $video->description ?? '',
                'duration' => $this->formatDuration($video->duration ?? 0),
                'duration_minutes' => $video->duration ?? 0,
                'video_url' => $video->video_url,
                'embed_url' => $embedUrl,
                'class_name' => $video->class_name,
                'progress' => $progressPercentage,
                'is_completed' => $progressPercentage >= 100
            ]
        ]);
    }

    public function updateProgress(Request $request, $id)
    {
        $user = $request->user();
        
        $request->validate([
            'progress' => 'required|integer|min:0|max:100'
        ]);

        $videoId = $id;
        $progress = $request->progress;
        $isCompleted = $progress >= 100;

        // Check if progress record exists
        $existingProgress = DB::table('video_progress')
            ->where('user_id', $user->user_id)
            ->where('video_id', $videoId)
            ->first();

        $wasCompleted = $existingProgress ? ($existingProgress->is_completed ?? false) : false;
        $isNewlyCompleted = $isCompleted && !$wasCompleted;

        if ($existingProgress) {
            DB::table('video_progress')
                ->where('user_id', $user->user_id)
                ->where('video_id', $videoId)
                ->update([
                    'progress' => $progress,
                    'is_completed' => $isCompleted,
                    'updated_at' => now()
                ]);
        } else {
            $nextProgressId = DB::table('video_progress')->max('progress_id') + 1;
            DB::table('video_progress')->insert([
                'progress_id' => $nextProgressId,
                'user_id' => $user->user_id,
                'video_id' => $videoId,
                'progress' => $progress,
                'is_completed' => $isCompleted,
                'updated_at' => now()
            ]);
        }

        // Award points for completing video (only once per video)
        if ($isNewlyCompleted) {
            $videoPoints = 10;
            DB::table('points')
                ->where('user_id', $user->user_id)
                ->increment('total_point', $videoPoints);
        }

        return response()->json([
            'success' => true,
            'message' => 'Progress video berhasil diperbarui.',
            'data' => [
                'progress' => $progress,
                'is_completed' => $isCompleted,
                'points_earned' => $isNewlyCompleted ? 10 : 0
            ]
        ]);
    }

    private function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return sprintf('%d jam %02d menit', $hours, $mins);
        }
        
        return sprintf('%d menit', $mins);
    }

    private function convertToEmbedUrl($url)
    {
        $youtubeVideoId = $this->extractYouTubeVideoId($url);
        if ($youtubeVideoId) {
            return $youtubeVideoId;
        }
        
        $driveFileId = $this->extractGoogleDriveFileId($url);
        if ($driveFileId) {
            return "https://drive.google.com/file/d/{$driveFileId}/preview";
        }
        
        return $url;
    }

    private function extractYouTubeVideoId($url)
    {
        $pattern = '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/';
        preg_match($pattern, $url, $matches);
        return isset($matches[2]) && strlen($matches[2]) === 11 ? $matches[2] : null;
    }

    private function extractGoogleDriveFileId($url)
    {
        if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        if (preg_match('/\/uc\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        if (preg_match('/\/open\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}

