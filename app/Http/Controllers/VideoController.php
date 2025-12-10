<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get user data from session and database
        $userData = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.user_id', session('user_id'))
            ->select('users.*', 'classes.class_name')
            ->first();

        if (!$userData) {
            return redirect()->route('login')->with('error', 'Data user tidak ditemukan.');
        }

        $user = [
            'name' => $userData->user_name,
            'class' => $userData->class_name,
            'class_id' => $userData->class_id
        ];

        // Get videos from database based on user's class
        $videos = DB::table('videos')
            ->where('class_id', $userData->class_id)
            ->get();
        
        // Get video progress for current user
        $videoProgress = DB::table('video_progress')
            ->where('user_id', session('user_id'))
            ->get()
            ->keyBy('video_id');

        // Prepare video data with progress
        $videoData = $videos->map(function($video) use ($videoProgress, $userData) {
            $progress = $videoProgress->get($video->video_id);
            $progressPercentage = $progress ? (int)$progress->progress : 0;
            
            // Ensure progress is between 0 and 100
            $progressPercentage = max(0, min(100, $progressPercentage));
            
            return [
                'id' => $video->video_id,
                'title' => $video->judul,
                'description' => $video->description ?? 'Video pembelajaran untuk ' . $userData->class_name,
                'duration' => $this->formatDuration($video->duration ?? 0),
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

        // Calculate overall progress percentage
        $overallProgress = $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100) : 0;

        // Get user stats for stat cards
        $totalPoints = DB::table('points')->where('user_id', session('user_id'))->value('total_point') ?? 0;
        $userRanking = DB::table('users')
            ->join('points', 'users.user_id', '=', 'points.user_id')
            ->where('users.role', 'siswa')
            ->where('points.total_point', '>', $totalPoints)
            ->count() + 1;

        return view('video-pembelajaran', compact(
            'user',
            'videoData',
            'totalVideos',
            'completedVideos', 
            'inProgressVideos',
            'notStartedVideos',
            'overallProgress',
            'totalPoints',
            'userRanking'
        ));
    }

    public function showVideo($id)
    {
        // Check if user is logged in
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get video data
        $video = DB::table('videos')
            ->join('classes', 'videos.class_id', '=', 'classes.class_id')
            ->where('videos.video_id', $id)
            ->select('videos.*', 'classes.class_name')
            ->first();

        if (!$video) {
            return redirect()->route('video.index')->with('error', 'Video tidak ditemukan.');
        }

        // Get user data
        $userData = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.user_id', session('user_id'))
            ->select('users.*', 'classes.class_name')
            ->first();

        // Get video progress for current user
        $progress = DB::table('video_progress')
            ->where('user_id', session('user_id'))
            ->where('video_id', $id)
            ->first();

        $progressPercentage = $progress ? (int)$progress->progress : 0;
        // Ensure progress is between 0 and 100
        $progressPercentage = max(0, min(100, $progressPercentage));

        // Convert video URL to embed format
        $embedUrl = $this->convertToEmbedUrl($video->video_url);

        return view('video-player', compact('video', 'userData', 'progressPercentage', 'embedUrl'));
    }

    public function updateProgress(Request $request)
    {
        $request->validate([
            'video_id' => 'required|integer',
            'progress' => 'required|integer|min:0|max:100'
        ]);

        $userId = session('user_id');
        $videoId = $request->video_id;
        $progress = $request->progress;
        $isCompleted = $progress >= 100;

        // Check if progress record exists
        $existingProgress = DB::table('video_progress')
            ->where('user_id', $userId)
            ->where('video_id', $videoId)
            ->first();

        $wasCompleted = $existingProgress ? ($existingProgress->is_completed ?? false) : false;
        $isNewlyCompleted = $isCompleted && !$wasCompleted;

        if ($existingProgress) {
            // Update existing progress
            DB::table('video_progress')
                ->where('user_id', $userId)
                ->where('video_id', $videoId)
                ->update([
                    'progress' => $progress,
                    'is_completed' => $isCompleted,
                    'updated_at' => now()
                ]);
        } else {
            // Insert new progress
            $nextProgressId = DB::table('video_progress')->max('progress_id') + 1;
            DB::table('video_progress')->insert([
                'progress_id' => $nextProgressId,
                'user_id' => $userId,
                'video_id' => $videoId,
                'progress' => $progress,
                'is_completed' => $isCompleted,
                'updated_at' => now()
            ]);
        }

        // Award points for completing video (only once per video)
        if ($isNewlyCompleted) {
            // Give 10 points for completing a video
            $videoPoints = 10;
            DB::table('points')
                ->where('user_id', $userId)
                ->increment('total_point', $videoPoints);
        }

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'is_completed' => $isCompleted
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

    /**
     * Convert video URL to embed format
     * Supports YouTube and Google Drive
     * For private YouTube videos, we'll use nocookie domain which sometimes works better
     */
    private function convertToEmbedUrl($url)
    {
        // Check if it's YouTube
        $youtubeVideoId = $this->extractYouTubeVideoId($url);
        if ($youtubeVideoId) {
            // Return video ID for YouTube IFrame API
            // We'll use the video ID directly in the view
            return $youtubeVideoId;
        }
        
        // Check if it's Google Drive
        $driveFileId = $this->extractGoogleDriveFileId($url);
        if ($driveFileId) {
            return "https://drive.google.com/file/d/{$driveFileId}/preview";
        }
        
        // Return original URL if not recognized
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
        // Pattern untuk berbagai format Google Drive URL
        // Pattern 1: /file/d/FILE_ID
        if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // Pattern 2: ?id=FILE_ID
        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // Pattern 3: /uc?id=FILE_ID
        if (preg_match('/\/uc\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // Pattern 4: /open?id=FILE_ID
        if (preg_match('/\/open\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}
