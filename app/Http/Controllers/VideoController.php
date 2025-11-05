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
        $videoData = $videos->map(function($video) use ($videoProgress) {
            $progress = $videoProgress->get($video->video_id);
            $progressPercentage = $progress ? $progress->progress : 0;
            
            return [
                'id' => $video->video_id,
                'title' => $video->judul,
                'description' => $video->description ?? 'Video pembelajaran untuk ' . $user['class'],
                'duration' => $this->formatDuration($video->duration ?? 0),
                'progress' => $progressPercentage,
                'is_completed' => $progressPercentage == 100,
                'is_in_progress' => $progressPercentage > 0 && $progressPercentage < 100,
                'video_url' => $video->video_url
            ];
        });

        // Calculate progress statistics
        $totalVideos = $videos->count();
        $completedVideos = $videoProgress->where('progress', 100)->count();
        $inProgressVideos = $videoProgress->where('progress', '>', 0)
                                       ->where('progress', '<', 100)->count();
        $notStartedVideos = $totalVideos - $completedVideos - $inProgressVideos;

        // Calculate overall progress percentage
        $overallProgress = $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100) : 0;

        return view('video-pembelajaran', compact(
            'user',
            'videoData',
            'totalVideos',
            'completedVideos', 
            'inProgressVideos',
            'notStartedVideos',
            'overallProgress'
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

        $progressPercentage = $progress ? $progress->progress : 0;

        return view('video-player', compact('video', 'userData', 'progressPercentage'));
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

        // Update or insert progress
        DB::table('video_progress')->updateOrInsert(
            ['user_id' => $userId, 'video_id' => $videoId],
            ['progress' => $progress, 'updated_at' => now()]
        );

        return response()->json(['success' => true]);
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
}
