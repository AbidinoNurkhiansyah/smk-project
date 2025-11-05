<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GameController extends Controller
{
    public function index()
    {
        // Cek session user
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get user's class_id from session
        $userClassId = session('class_id');
        
        // Get user info
        $user = DB::table('users')
            ->where('user_id', session('user_id'))
            ->first();

        // Get active teacher quizzes for the user's class
        $quizzes = DB::table('teacher_quizzes')
            ->where('class_id', $userClassId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($quizzes->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Belum ada quiz yang tersedia untuk kelas Anda!');
        }

        return view('game.index', compact('quizzes', 'user'));
    }

    public function play($id)
    {
        // Cek session user
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get teacher quiz by ID
        $quiz = DB::table('teacher_quizzes')
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
            
        if (!$quiz) {
            return redirect()->route('game.index')->with('error', 'Quiz tidak ditemukan!');
        }

        // Get quiz questions
        $questions = DB::table('teacher_quiz_questions')
            ->where('quiz_id', $id)
            ->orderBy('order_number')
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('game.index')->with('error', 'Quiz belum memiliki soal!');
        }

        // Get options for all questions
        $questionIds = $questions->pluck('id');
        $allOptions = DB::table('teacher_quiz_options')
            ->whereIn('question_id', $questionIds)
            ->get()
            ->groupBy('question_id');

        return view('game.play', compact('quiz', 'questions', 'allOptions'));
    }

    public function submit(Request $request, $id)
    {
        // Cek session user
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'answers' => 'required|array'
        ]);

        // Get teacher quiz by ID
        $quiz = DB::table('teacher_quizzes')
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
            
        if (!$quiz) {
            return redirect()->route('game.index')->with('error', 'Quiz tidak ditemukan!');
        }
        
        // Get quiz questions
        $questions = DB::table('teacher_quiz_questions')
            ->where('quiz_id', $id)
            ->orderBy('order_number')
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('game.index')->with('error', 'Quiz tidak ditemukan!');
        }

        $totalScore = 0;
        $correctAnswers = 0;
        $totalQuestions = $questions->count();

        // Process each answer
        foreach ($questions as $question) {
            $userAnswer = $request->answers[$question->id] ?? null;
            $isCorrect = $userAnswer === $question->correct_answer;
            $points = $isCorrect ? $question->points : 0;
            
            if ($isCorrect) {
                $correctAnswers++;
                $totalScore += $points;
            }

            // Save quiz result to database (simplified for now)
            if ($userAnswer) {
                // You can create a new table for teacher quiz results if needed
                // For now, we'll just update the points
            }
        }

        // Update total points user
        DB::table('points')
            ->where('user_id', session('user_id'))
            ->increment('total_point', $totalScore);

        // Calculate percentage
        $percentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

        // Determine grade
        $grade = 'E';
        if ($percentage >= 90) $grade = 'A';
        elseif ($percentage >= 80) $grade = 'B';
        elseif ($percentage >= 70) $grade = 'C';
        elseif ($percentage >= 60) $grade = 'D';

        return view('game.result', compact('totalScore', 'correctAnswers', 'totalQuestions', 'percentage', 'grade', 'quiz'));
    }

    public function leaderboard()
    {
        // Get user's class_id from session
        $userClassId = session('class_id');
        
        // Get users with their points from the same class only
        $leaderboard = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->join('points', 'users.user_id', '=', 'points.user_id')
            ->select('users.user_id', 'users.user_name', 'classes.class_name', 'points.total_point')
            ->where('users.role', 'siswa')
            ->where('users.class_id', $userClassId)
            ->orderBy('points.total_point', 'desc')
            ->get();

        // Add ranking to each user
        $leaderboard = $leaderboard->map(function ($user, $index) {
            $user->ranking = $index + 1;
            return $user;
        });

        return view('game.leaderboard', compact('leaderboard'));
    }
}
