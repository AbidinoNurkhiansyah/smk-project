<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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
            'avatar' => asset('image/logo.png'),
            'profile_picture' => $userData->profile_picture ?? null
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

        // Calculate progress statistics
        $totalVideos = $videos->count();
        $completedVideos = $videoProgress->where('progress', 100)->count();
        $inProgressVideos = $videoProgress->where('progress', '>', 0)
                                       ->where('progress', '<', 100)->count();
        $notStartedVideos = $totalVideos - $completedVideos - $inProgressVideos;

        // Calculate total watch time (using duration from videos table)
        $totalWatchTime = $videos->sum('duration') ?? 0; // in minutes

        // Calculate overall progress percentage
        $overallProgress = $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100) : 0;

        // Get user points
        $userPoints = DB::table('points')
            ->where('user_id', 1)
            ->value('total_point') ?? 0;
            
        // If no points data, create default entry
        if ($userPoints === null) {
            DB::table('points')->insert([
                'user_id' => 1,
                'total_point' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $userPoints = 0;
        }

        // Prepare video data with progress
        $videoData = $videos->map(function($video) use ($videoProgress) {
            $progress = $videoProgress->get($video->video_id);
            $progressPercentage = $progress ? $progress->progress : 0;
            
            return [
                'id' => $video->video_id,
                'title' => $video->judul,
                'category' => 'General', // Default category since not in DB
                'duration' => $this->formatDuration($video->duration ?? 0),
                'thumbnail' => 'play', // Default thumbnail
                'progress' => $progressPercentage,
                'is_completed' => $progressPercentage == 100,
                'is_in_progress' => $progressPercentage > 0 && $progressPercentage < 100
            ];
        });

        return view('dashboard', compact(
            'user',
            'totalVideos',
            'completedVideos', 
            'inProgressVideos',
            'notStartedVideos',
            'totalWatchTime',
            'overallProgress',
            'userPoints',
            'videoData'
        ));
    }

    public function getProgressData()
    {
        // Get user's class_id from session
        $userClassId = session('class_id');
        $videos = DB::table('videos')
            ->where('class_id', $userClassId)
            ->get();
        $videoProgress = DB::table('video_progress')
            ->where('user_id', session('user_id'))
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

        return response()->json($progressData);
    }

    public function updateVideoProgress(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,video_id',
            'progress' => 'required|integer|min:0|max:100'
        ]);

        DB::table('video_progress')->updateOrInsert(
            ['video_id' => $request->video_id, 'user_id' => session('user_id')],
            [
                'progress' => $request->progress,
                'is_completed' => $request->progress == 100,
                'updated_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'progress' => $request->progress
        ]);
    }

    public function welcome()
    {
        // Get statistics from database (same as admin dashboard)
        $totalVideos = DB::table('videos')->count();
        $totalStudents = DB::table('users')->where('role', 'siswa')->count();
        
        // Calculate satisfaction rate based on average video progress and quiz scores
        // Average video progress (from all students)
        $avgVideoProgress = DB::table('video_progress')
            ->select(DB::raw('AVG(progress) as avg_progress'))
            ->value('avg_progress') ?? 0;
        
        // Calculate average quiz score per quiz attempt (similar to QuizAnalyticsController)
        $quizScores = DB::table('teacher_quiz_answers')
            ->join('teacher_quizzes', 'teacher_quiz_answers.quiz_id', '=', 'teacher_quizzes.id')
            ->join('teacher_quiz_questions', 'teacher_quiz_answers.question_id', '=', 'teacher_quiz_questions.id')
            ->select(
                'teacher_quiz_answers.quiz_id',
                'teacher_quiz_answers.user_id',
                DB::raw('COUNT(*) as total_questions'),
                DB::raw('SUM(CASE WHEN teacher_quiz_answers.is_correct = 1 THEN 1 ELSE 0 END) as correct_answers')
            )
            ->groupBy('teacher_quiz_answers.quiz_id', 'teacher_quiz_answers.user_id')
            ->get();
        
        // Calculate average score percentage from all quiz attempts
        $avgQuizScore = 0;
        if ($quizScores->count() > 0) {
            $totalScore = 0;
            foreach ($quizScores as $quiz) {
                $score = $quiz->total_questions > 0 ? ($quiz->correct_answers / $quiz->total_questions) * 100 : 0;
                $totalScore += $score;
            }
            $avgQuizScore = $totalScore / $quizScores->count();
        }
        
        // Calculate overall satisfaction (weighted average: 60% video progress, 40% quiz score)
        // Or use video progress only if no quiz data
        $satisfactionRate = 0;
        if ($avgQuizScore > 0 && $avgVideoProgress > 0) {
            $satisfactionRate = round(($avgVideoProgress * 0.6) + ($avgQuizScore * 0.4));
        } elseif ($avgVideoProgress > 0) {
            $satisfactionRate = round($avgVideoProgress);
        } elseif ($avgQuizScore > 0) {
            $satisfactionRate = round($avgQuizScore);
        } else {
            // Default to 0 if no data
            $satisfactionRate = 0;
        }
        
        // Ensure satisfaction rate is between 0-100
        $satisfactionRate = max(0, min(100, $satisfactionRate));
        
        return view('welcome', compact(
            'totalVideos',
            'totalStudents',
            'satisfactionRate'
        ));
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