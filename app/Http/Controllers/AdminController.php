<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Get selected class filter
        $selectedClass = $request->get('class_id', 'all');
        
        // Get all classes for filter dropdown
        $classes = DB::table('classes')->get();
        
        // Build base queries
        $studentsQuery = DB::table('users')->where('role', 'siswa');
        $videosQuery = DB::table('videos');
        $quizzesQuery = DB::table('teacher_quizzes')->where('is_active', true);
        
        // Apply class filter if not 'all'
        if ($selectedClass !== 'all') {
            $studentsQuery->where('class_id', $selectedClass);
            $videosQuery->where('class_id', $selectedClass);
            $quizzesQuery->where('class_id', $selectedClass);
        }
        
        // Get statistics
        $totalStudents = $studentsQuery->count();
        $totalVideos = $videosQuery->count();
        $totalQuizzes = $quizzesQuery->count();
        $totalClasses = DB::table('classes')->count();

        // Get recent activities with class filter
        $recentVideos = $videosQuery->orderBy('video_id', 'desc')->limit(5)->get();

        $recentProgress = DB::table('video_progress')
            ->join('users', 'video_progress.user_id', '=', 'users.user_id')
            ->join('videos', 'video_progress.video_id', '=', 'videos.video_id')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->select('video_progress.*', 'users.user_name', 'videos.judul', 'classes.class_name')
            ->when($selectedClass !== 'all', function($query) use ($selectedClass) {
                return $query->where('users.class_id', $selectedClass);
            })
            ->orderBy('video_progress.progress', 'desc')
            ->limit(10)
            ->get();

        // Get class-specific statistics
        $classStats = [];
        foreach ($classes as $class) {
            $classStats[] = [
                'class_id' => $class->class_id,
                'class_name' => $class->class_name,
                'student_count' => DB::table('users')->where('role', 'siswa')->where('class_id', $class->class_id)->count(),
                'video_count' => DB::table('videos')->where('class_id', $class->class_id)->count(),
                'quiz_count' => DB::table('teacher_quizzes')->where('class_id', $class->class_id)->where('is_active', true)->count()
            ];
        }

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalVideos',
            'totalQuizzes',
            'totalClasses',
            'recentVideos',
            'recentProgress',
            'classes',
            'selectedClass',
            'classStats'
        ));
    }

    public function videos(Request $request)
    {
        $selectedClass = $request->get('class_id', 'all');
        $classes = DB::table('classes')->get();
        
        $videosQuery = DB::table('videos')
            ->join('classes', 'videos.class_id', '=', 'classes.class_id')
            ->select('videos.*', 'classes.class_name');
            
        if ($selectedClass !== 'all') {
            $videosQuery->where('videos.class_id', $selectedClass);
        }
        
        $videos = $videosQuery->orderBy('videos.video_id', 'desc')->get();

        return view('admin.videos', compact('videos', 'classes', 'selectedClass'));
    }

    public function addVideo(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'video_url' => 'required|url'
        ]);

        // Get next video_id
        $nextVideoId = DB::table('videos')->max('video_id') + 1;
        
        DB::table('videos')->insert([
            'video_id' => $nextVideoId,
            'judul' => $request->judul,
            'description' => $request->deskripsi,
            'video_url' => $request->video_url
        ]);

        return redirect()->route('admin.videos')->with('success', 'Video berhasil ditambahkan!');
    }

    public function editVideo($id)
    {
        $video = DB::table('videos')->where('video_id', $id)->first();
        
        if (!$video) {
            return redirect()->route('admin.videos')->with('error', 'Video tidak ditemukan!');
        }

        return view('admin.edit-video', compact('video'));
    }

    public function updateVideo(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'video_url' => 'required|url'
        ]);

        DB::table('videos')
            ->where('video_id', $id)
            ->update([
                'judul' => $request->judul,
                'description' => $request->deskripsi,
                'video_url' => $request->video_url
            ]);

        return redirect()->route('admin.videos')->with('success', 'Video berhasil diperbarui!');
    }

    public function deleteVideo($id)
    {
        // Hapus data terkait terlebih dahulu
        DB::table('video_progress')->where('video_id', $id)->delete();
        
        // Hapus video
        DB::table('videos')->where('video_id', $id)->delete();
        
        return redirect()->route('admin.videos')->with('success', 'Video berhasil dihapus!');
    }


    public function leaderboard(Request $request)
    {
        $selectedClass = $request->get('class_id', 'all');
        $classes = DB::table('classes')->get();
        
        $leaderboardQuery = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->join('points', 'users.user_id', '=', 'points.user_id')
            ->select('users.user_id', 'users.user_name', 'classes.class_name', 'points.total_point')
            ->where('users.role', 'siswa');
            
        if ($selectedClass !== 'all') {
            $leaderboardQuery->where('users.class_id', $selectedClass);
        }
        
        $leaderboard = $leaderboardQuery->orderBy('points.total_point', 'desc')->get();

        // Add ranking
        $leaderboard = $leaderboard->map(function ($user, $index) {
            $user->ranking = $index + 1;
            return $user;
        });

        return view('admin.leaderboard', compact('leaderboard', 'classes', 'selectedClass'));
    }



    public function updateGame(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'points' => 'required|integer|min:1',
            'difficulty' => 'required|string'
        ]);

        DB::table('challenges')
            ->where('challenge_id', $id)
            ->update([
                'question' => $request->title,
                'type' => $request->type,
                'difficulty' => $request->difficulty,
                'point' => $request->points
            ]);

        // Update options
        if ($request->has('options')) {
            // Delete existing options
            DB::table('challenge_options')->where('challenge_id', $id)->delete();
            
            // Insert new options
            $optionLabels = ['A', 'B', 'C', 'D', 'E', 'F'];
            $nextOptionId = DB::table('challenge_options')->max('option_id') + 1;
            
            foreach ($request->options as $index => $option) {
                if (!empty($option['text'])) {
                    DB::table('challenge_options')->insert([
                        'option_id' => $nextOptionId,
                        'challenge_id' => $id,
                        'option_label' => $optionLabels[$index] ?? 'A',
                        'option_text' => $option['text']
                    ]);
                    $nextOptionId++;
                }
            }
        }

        return redirect()->route('admin.games')->with('success', 'Game berhasil diperbarui!');
    }

    public function deleteGame($id)
    {
        // Hapus data terkait terlebih dahulu
        DB::table('user_challenges')->where('challenge_id', $id)->delete();
        DB::table('challenge_options')->where('challenge_id', $id)->delete();
        
        // Hapus challenge
        DB::table('challenges')->where('challenge_id', $id)->delete();
        
        return redirect()->route('admin.games')->with('success', 'Game berhasil dihapus!');
    }

    public function gameOptions($id)
    {
        $game = DB::table('challenges')->where('challenge_id', $id)->first();
        $options = DB::table('challenge_options')->where('challenge_id', $id)->get();
        
        if (!$game) {
            return redirect()->route('admin.games')->with('error', 'Game tidak ditemukan!');
        }

        return view('admin.game-options', compact('game', 'options'));
    }

    public function addGameOption(Request $request, $id)
    {
        $request->validate([
            'option_text' => 'required|string|max:255',
            'is_correct' => 'nullable|boolean'
        ]);

        $nextOptionId = DB::table('challenge_options')->max('option_id') + 1;
        $optionLabels = ['A', 'B', 'C', 'D', 'E', 'F'];
        $existingOptions = DB::table('challenge_options')->where('challenge_id', $id)->count();
        
        DB::table('challenge_options')->insert([
            'option_id' => $nextOptionId,
            'challenge_id' => $id,
            'option_label' => $optionLabels[$existingOptions] ?? 'A',
            'option_text' => $request->option_text
        ]);

        return redirect()->route('admin.game-options', $id)->with('success', 'Opsi jawaban berhasil ditambahkan!');
    }

    public function updateGameOption(Request $request, $id, $optionId)
    {
        $request->validate([
            'option_text' => 'required|string|max:255'
        ]);

        DB::table('challenge_options')
            ->where('option_id', $optionId)
            ->where('challenge_id', $id)
            ->update([
                'option_text' => $request->option_text
            ]);

        return redirect()->route('admin.game-options', $id)->with('success', 'Opsi jawaban berhasil diperbarui!');
    }

    public function deleteGameOption($id, $optionId)
    {
        DB::table('challenge_options')
            ->where('option_id', $optionId)
            ->where('challenge_id', $id)
            ->delete();

        return redirect()->route('admin.game-options', $id)->with('success', 'Opsi jawaban berhasil dihapus!');
    }

    public function quizQuestions($id)
    {
        $game = DB::table('challenges')->where('challenge_id', $id)->first();
        $questions = DB::table('questions')
            ->where('quiz_id', $id)
            ->orderBy('question_number')
            ->get();
        
        if (!$game) {
            return redirect()->route('admin.games')->with('error', 'Game tidak ditemukan!');
        }

        return view('admin.quiz-questions', compact('game', 'questions'));
    }

    public function addQuestion(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'correct_answer' => 'required|string|in:A,B,C,D',
            'options' => 'required|array',
            'options.A' => 'required|string',
            'options.B' => 'required|string',
            'options.C' => 'required|string',
            'options.D' => 'required|string'
        ]);

        // Get next question number
        $nextQuestionNumber = DB::table('questions')->where('quiz_id', $id)->max('question_number') + 1;
        
        // Get next question_id
        $nextQuestionId = DB::table('questions')->max('question_id') + 1;
        
        // Insert question
        DB::table('questions')->insert([
            'question_id' => $nextQuestionId,
            'quiz_id' => $id,
            'question_text' => $request->question_text,
            'correct_answer' => $request->correct_answer,
            'question_number' => $nextQuestionNumber
        ]);

        // Insert options
        $nextOptionId = DB::table('question_options')->max('option_id') + 1;
        foreach ($request->options as $label => $text) {
            DB::table('question_options')->insert([
                'option_id' => $nextOptionId,
                'question_id' => $nextQuestionId,
                'option_label' => $label,
                'option_text' => $text
            ]);
            $nextOptionId++;
        }

        return redirect()->route('admin.quiz-questions', $id)->with('success', 'Soal berhasil ditambahkan!');
    }

    public function updateQuestion(Request $request, $id, $questionId)
    {
        $request->validate([
            'question_text' => 'required|string',
            'correct_answer' => 'required|string|in:A,B,C,D',
            'options' => 'required|array',
            'options.A' => 'required|string',
            'options.B' => 'required|string',
            'options.C' => 'required|string',
            'options.D' => 'required|string'
        ]);

        // Update question
        DB::table('questions')
            ->where('question_id', $questionId)
            ->where('quiz_id', $id)
            ->update([
                'question_text' => $request->question_text,
                'correct_answer' => $request->correct_answer
            ]);

        // Update options
        foreach ($request->options as $label => $text) {
            DB::table('question_options')
                ->where('question_id', $questionId)
                ->where('option_label', $label)
                ->update(['option_text' => $text]);
        }

        return redirect()->route('admin.quiz-questions', $id)->with('success', 'Soal berhasil diperbarui!');
    }

    public function deleteQuestion($id, $questionId)
    {
        // Delete question options first
        DB::table('question_options')->where('question_id', $questionId)->delete();
        
        // Delete question
        DB::table('questions')
            ->where('question_id', $questionId)
            ->where('quiz_id', $id)
            ->delete();

        return redirect()->route('admin.quiz-questions', $id)->with('success', 'Soal berhasil dihapus!');
    }

    public function students(Request $request)
    {
        $selectedClass = $request->get('class_id', 'all');
        $classes = DB::table('classes')->get();
        
        $studentsQuery = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.role', 'siswa')
            ->select('users.*', 'classes.class_name');
        
        if ($selectedClass !== 'all') {
            $studentsQuery->where('users.class_id', $selectedClass);
        }
        
        $students = $studentsQuery->orderBy('users.created_at', 'desc')->get();

        return view('admin.students', compact('students', 'classes', 'selectedClass'));
    }

    public function deleteStudent($id)
    {
        // Cek apakah siswa ada
        $student = DB::table('users')->where('user_id', $id)->where('role', 'siswa')->first();
        
        if (!$student) {
            return redirect()->route('admin.students')->with('error', 'Siswa tidak ditemukan!');
        }

        // Hapus data terkait terlebih dahulu
        DB::table('user_challenges')->where('user_id', $id)->delete();
        DB::table('video_progress')->where('user_id', $id)->delete();
        DB::table('user_video_stats')->where('user_id', $id)->delete();
        DB::table('user_quiz_answers')->where('user_id', $id)->delete();
        
        // Hapus data leaderboard yang mereferensikan points
        DB::table('leaderboard')->where('user_id', $id)->delete();
        
        // Hapus points setelah leaderboard
        DB::table('points')->where('user_id', $id)->delete();
        
        // Hapus user
        DB::table('users')->where('user_id', $id)->delete();
        
        return redirect()->route('admin.students')->with('success', 'Akun siswa berhasil dihapus!');
    }

    public function studentProgress($id)
    {
        $student = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.user_id', $id)
            ->select('users.*', 'classes.class_name')
            ->first();

        if (!$student) {
            return redirect()->route('admin.students')->with('error', 'Siswa tidak ditemukan!');
        }

        // Get video progress
        $videoProgress = DB::table('video_progress')
            ->join('videos', 'video_progress.video_id', '=', 'videos.video_id')
            ->where('video_progress.user_id', $id)
            ->select('video_progress.*', 'videos.judul')
            ->get();

        // Get game progress
        $gameProgress = DB::table('user_challenges')
            ->join('challenges', 'user_challenges.challenge_id', '=', 'challenges.challenge_id')
            ->where('user_challenges.user_id', $id)
            ->select('user_challenges.*', 'challenges.question', 'challenges.point')
            ->get();

        // Get points
        $points = DB::table('points')->where('user_id', $id)->first();

        // Calculate statistics
        $totalVideos = DB::table('videos')->count();
        $completedVideos = $videoProgress->where('progress', 100)->count();
        $totalGames = DB::table('challenges')->count();
        $completedGames = $gameProgress->where('is_completed', true)->count();

        return view('admin.student-progress', compact(
            'student',
            'videoProgress',
            'gameProgress',
            'points',
            'totalVideos',
            'completedVideos',
            'totalGames',
            'completedGames'
        ));
    }

    public function analytics()
    {
        // Get class-wise progress
        $classProgress = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->join('video_progress', 'users.user_id', '=', 'video_progress.user_id')
            ->join('videos', 'video_progress.video_id', '=', 'videos.video_id')
            ->select('classes.class_name', DB::raw('AVG(video_progress.progress) as avg_progress'))
            ->groupBy('classes.class_id', 'classes.class_name')
            ->get();

        // Get most popular videos
        $popularVideos = DB::table('videos')
            ->join('video_progress', 'videos.video_id', '=', 'video_progress.video_id')
            ->select('videos.judul', DB::raw('COUNT(*) as view_count'))
            ->groupBy('videos.video_id', 'videos.judul')
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        // Get leaderboard
        $leaderboard = DB::table('users')
            ->join('points', 'users.user_id', '=', 'points.user_id')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.role', 'siswa')
            ->select('users.user_name', 'classes.class_name', 'points.total_point')
            ->orderBy('points.total_point', 'desc')
            ->limit(10)
            ->get();

        return view('admin.analytics', compact('classProgress', 'popularVideos', 'leaderboard'));
    }




}
