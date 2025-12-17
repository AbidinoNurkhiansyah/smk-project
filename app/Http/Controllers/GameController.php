<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

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
        
        // Get user info with class name
        $user = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.user_id', session('user_id'))
            ->select('users.*', 'classes.class_name')
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

        // Get completed quiz IDs for current user
        $completedQuizIds = DB::table('teacher_quiz_answers')
            ->where('user_id', session('user_id'))
            ->distinct()
            ->pluck('quiz_id')
            ->toArray();

        // Add completion status to each quiz
        $quizzes = $quizzes->map(function($quiz) use ($completedQuizIds) {
            $quiz->is_completed = in_array($quiz->id, $completedQuizIds);
            return $quiz;
        });

        // Count completed quizzes
        $completedQuizzesCount = count($completedQuizIds);

        return view('game.index', compact('quizzes', 'user', 'completedQuizzesCount'));
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

        // Store quiz start time in session (using Indonesia timezone)
        $quizStartTime = Carbon::now('Asia/Jakarta')->timestamp;
        $timeLimitSeconds = $quiz->time_limit * 60;
        $quizEndTime = $quizStartTime + $timeLimitSeconds;
        
        // Store in session
        Session::put('quiz_start_time_' . $id, $quizStartTime);
        Session::put('quiz_end_time_' . $id, $quizEndTime);
        Session::put('quiz_time_limit_' . $id, $timeLimitSeconds);

        // Get options for all questions
        $questionIds = $questions->pluck('id');
        $allOptions = DB::table('teacher_quiz_options')
            ->whereIn('question_id', $questionIds)
            ->get()
            ->groupBy('question_id');

        return view('game.play', compact('quiz', 'questions', 'allOptions'));
    }

    // API endpoint to get real-time server time and remaining time
    public function getQuizTime($id)
    {
        $quizStartTime = Session::get('quiz_start_time_' . $id);
        $quizEndTime = Session::get('quiz_end_time_' . $id);
        $timeLimit = Session::get('quiz_time_limit_' . $id);
        
        if (!$quizStartTime || !$quizEndTime) {
            return response()->json([
                'error' => 'Quiz session not found',
                'server_time' => Carbon::now('Asia/Jakarta')->timestamp,
                'timezone' => 'Asia/Jakarta'
            ], 404);
        }
        
        $currentTime = Carbon::now('Asia/Jakarta')->timestamp;
        $remainingTime = max(0, $quizEndTime - $currentTime);
        
        return response()->json([
            'server_time' => $currentTime,
            'start_time' => $quizStartTime,
            'end_time' => $quizEndTime,
            'remaining_time' => $remainingTime,
            'time_limit' => $timeLimit,
            'timezone' => 'Asia/Jakarta',
            'time_string' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
        ]);
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

        // Check if this is first attempt (before processing answers)
        $hasPreviousAttempt = DB::table('teacher_quiz_answers')
            ->where('user_id', session('user_id'))
            ->where('quiz_id', $id)
            ->exists();

        // Process each answer
        foreach ($questions as $question) {
            $userAnswer = $request->answers[$question->id] ?? null;
            $isCorrect = $userAnswer === $question->correct_answer;
            
            if ($isCorrect) {
                $correctAnswers++;
            }

            // Save quiz result to database
            if ($userAnswer) {
                // Check if answer already exists (to prevent duplicates)
                $existingAnswer = DB::table('teacher_quiz_answers')
                    ->where('user_id', session('user_id'))
                    ->where('quiz_id', $id)
                    ->where('question_id', $question->id)
                    ->first();

                if ($existingAnswer) {
                    // Update existing answer
                    DB::table('teacher_quiz_answers')
                        ->where('id', $existingAnswer->id)
                        ->update([
                            'user_answer' => $userAnswer,
                            'is_correct' => $isCorrect,
                            'answered_at' => now(),
                            'updated_at' => now()
                        ]);
                } else {
                    // Insert new answer
                    DB::table('teacher_quiz_answers')->insert([
                        'user_id' => session('user_id'),
                        'quiz_id' => $id,
                        'question_id' => $question->id,
                        'user_answer' => $userAnswer,
                        'is_correct' => $isCorrect,
                        'answered_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

        // Calculate percentage
        $percentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        
        // Calculate points based on performance (proporsional)
        // Base points: 10-50 points per quiz based on percentage
        // Formula: base_points * (percentage / 100)
        // Minimum: 5 points if >= 60%, 0 if < 60%
        $basePoints = 30; // Base points for completing quiz
        $pointsEarned = 0;
        
        if ($percentage >= 60) {
            // Scale points from 10 to 50 based on percentage
            // 60% = 10 points, 100% = 50 points
            $pointsEarned = round(10 + (($percentage - 60) / 40) * 40);
        } else {
            // Below 60% gets minimal points (encouragement)
            $pointsEarned = round(($percentage / 60) * 5);
        }
        
        // Bonus for perfect score
        if ($percentage == 100) {
            $pointsEarned += 10; // Bonus 10 points for perfect score
        }

        // Only add points on first attempt to prevent point inflation
        // Retakes don't add more points (encourages students to do well on first try)
        if (!$hasPreviousAttempt && $pointsEarned > 0) {
            DB::table('points')
                ->where('user_id', session('user_id'))
                ->increment('total_point', $pointsEarned);
        }
        
        $totalScore = $pointsEarned;

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

        // Get stats for stat cards
        $totalQuizzes = DB::table('teacher_quizzes')
            ->where('class_id', $userClassId)
            ->where('is_active', true)
            ->count();
        
        $totalPoints = DB::table('points')
            ->where('user_id', session('user_id'))
            ->value('total_point') ?? 0;
        
        $completedQuizzes = DB::table('teacher_quiz_answers')
            ->where('user_id', session('user_id'))
            ->distinct('quiz_id')
            ->count();
        
        $userRanking = DB::table('users')
            ->join('points', 'users.user_id', '=', 'points.user_id')
            ->where('users.role', 'siswa')
            ->where('users.class_id', $userClassId)
            ->where('points.total_point', '>', $totalPoints)
            ->count() + 1;

        return view('game.leaderboard', compact('leaderboard', 'totalQuizzes', 'totalPoints', 'completedQuizzes', 'userRanking'));
    }
}
