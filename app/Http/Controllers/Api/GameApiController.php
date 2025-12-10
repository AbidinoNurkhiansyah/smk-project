<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class GameApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $userClassId = $user->class_id;
        
        // Get active teacher quizzes for the user's class
        $quizzes = DB::table('teacher_quizzes')
            ->where('class_id', $userClassId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $quizzes->map(function($quiz) {
                return [
                    'id' => $quiz->id,
                    'quiz_title' => $quiz->quiz_title,
                    'quiz_description' => $quiz->quiz_description,
                    'total_questions' => $quiz->total_questions,
                    'time_limit' => $quiz->time_limit,
                    'difficulty' => $quiz->difficulty,
                    'created_at' => $quiz->created_at,
                ];
            })
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        // Get teacher quiz by ID
        $quiz = DB::table('teacher_quizzes')
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
            
        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan!'
            ], 404);
        }

        // Get quiz questions
        $questions = DB::table('teacher_quiz_questions')
            ->where('quiz_id', $id)
            ->orderBy('order_number')
            ->get();

        if ($questions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz belum memiliki soal!'
            ], 404);
        }

        // Store quiz start time in session
        $quizStartTime = Carbon::now('Asia/Jakarta')->timestamp;
        $timeLimitSeconds = $quiz->time_limit * 60;
        $quizEndTime = $quizStartTime + $timeLimitSeconds;
        
        Session::put('quiz_start_time_' . $id, $quizStartTime);
        Session::put('quiz_end_time_' . $id, $quizEndTime);
        Session::put('quiz_time_limit_' . $id, $timeLimitSeconds);

        // Get options for all questions
        $questionIds = $questions->pluck('id');
        $allOptions = DB::table('teacher_quiz_options')
            ->whereIn('question_id', $questionIds)
            ->get()
            ->groupBy('question_id');

        $questionsData = $questions->map(function($question) use ($allOptions) {
            $options = $allOptions->get($question->id, collect())->map(function($option) {
                return [
                    'label' => $option->option_label,
                    'text' => $option->option_text
                ];
            })->values();

            return [
                'id' => $question->id,
                'question' => $question->question,
                'image' => $question->image ? url('storage/' . $question->image) : null,
                'points' => $question->points,
                'order_number' => $question->order_number,
                'options' => $options,
                // Don't send correct_answer to client
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'quiz' => [
                    'id' => $quiz->id,
                    'quiz_title' => $quiz->quiz_title,
                    'quiz_description' => $quiz->quiz_description,
                    'total_questions' => $quiz->total_questions,
                    'time_limit' => $quiz->time_limit,
                    'difficulty' => $quiz->difficulty,
                ],
                'start_time' => $quizStartTime,
                'end_time' => $quizEndTime,
                'time_limit_seconds' => $timeLimitSeconds,
                'questions' => $questionsData
            ]
        ]);
    }

    public function getQuizTime(Request $request, $id)
    {
        $quizStartTime = Session::get('quiz_start_time_' . $id);
        $quizEndTime = Session::get('quiz_end_time_' . $id);
        $timeLimit = Session::get('quiz_time_limit_' . $id);
        
        if (!$quizStartTime || !$quizEndTime) {
            return response()->json([
                'success' => false,
                'error' => 'Quiz session not found',
                'server_time' => Carbon::now('Asia/Jakarta')->timestamp,
                'timezone' => 'Asia/Jakarta'
            ], 404);
        }
        
        $currentTime = Carbon::now('Asia/Jakarta')->timestamp;
        $remainingTime = max(0, $quizEndTime - $currentTime);
        
        return response()->json([
            'success' => true,
            'data' => [
                'server_time' => $currentTime,
                'start_time' => $quizStartTime,
                'end_time' => $quizEndTime,
                'remaining_time' => $remainingTime,
                'time_limit' => $timeLimit,
                'timezone' => 'Asia/Jakarta',
                'time_string' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
            ]
        ]);
    }

    public function submit(Request $request, $id)
    {
        $user = $request->user();
        
        $request->validate([
            'answers' => 'required|array'
        ]);

        // Get teacher quiz by ID
        $quiz = DB::table('teacher_quizzes')
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
            
        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan!'
            ], 404);
        }
        
        // Get quiz questions
        $questions = DB::table('teacher_quiz_questions')
            ->where('quiz_id', $id)
            ->orderBy('order_number')
            ->get();

        if ($questions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan!'
            ], 404);
        }

        $totalScore = 0;
        $correctAnswers = 0;
        $totalQuestions = $questions->count();

        // Check if this is first attempt
        $hasPreviousAttempt = DB::table('teacher_quiz_answers')
            ->where('user_id', $user->user_id)
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
                $existingAnswer = DB::table('teacher_quiz_answers')
                    ->where('user_id', $user->user_id)
                    ->where('quiz_id', $id)
                    ->where('question_id', $question->id)
                    ->first();

                if ($existingAnswer) {
                    DB::table('teacher_quiz_answers')
                        ->where('id', $existingAnswer->id)
                        ->update([
                            'user_answer' => $userAnswer,
                            'is_correct' => $isCorrect,
                            'answered_at' => now(),
                            'updated_at' => now()
                        ]);
                } else {
                    DB::table('teacher_quiz_answers')->insert([
                        'user_id' => $user->user_id,
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
        
        // Calculate points based on performance
        $basePoints = 30;
        $pointsEarned = 0;
        
        if ($percentage >= 60) {
            $pointsEarned = round(10 + (($percentage - 60) / 40) * 40);
        } else {
            $pointsEarned = round(($percentage / 60) * 5);
        }
        
        if ($percentage == 100) {
            $pointsEarned += 10;
        }

        // Only add points on first attempt
        if (!$hasPreviousAttempt && $pointsEarned > 0) {
            DB::table('points')
                ->where('user_id', $user->user_id)
                ->increment('total_point', $pointsEarned);
        }
        
        $totalScore = $pointsEarned;

        // Determine grade
        $grade = 'E';
        if ($percentage >= 90) $grade = 'A';
        elseif ($percentage >= 80) $grade = 'B';
        elseif ($percentage >= 70) $grade = 'C';
        elseif ($percentage >= 60) $grade = 'D';

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil disubmit!',
            'data' => [
                'total_score' => $totalScore,
                'correct_answers' => $correctAnswers,
                'total_questions' => $totalQuestions,
                'percentage' => $percentage,
                'grade' => $grade,
                'points_earned' => $pointsEarned,
                'is_first_attempt' => !$hasPreviousAttempt
            ]
        ]);
    }

    public function leaderboard(Request $request)
    {
        $user = $request->user();
        $userClassId = $user->class_id;
        
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
        $leaderboard = $leaderboard->map(function ($userItem, $index) {
            return [
                'ranking' => $index + 1,
                'user_id' => $userItem->user_id,
                'user_name' => $userItem->user_name,
                'class_name' => $userItem->class_name,
                'total_point' => $userItem->total_point,
            ];
        });

        // Get stats
        $totalQuizzes = DB::table('teacher_quizzes')
            ->where('class_id', $userClassId)
            ->where('is_active', true)
            ->count();
        
        $totalPoints = DB::table('points')
            ->where('user_id', $user->user_id)
            ->value('total_point') ?? 0;
        
        $completedQuizzes = DB::table('teacher_quiz_answers')
            ->where('user_id', $user->user_id)
            ->distinct('quiz_id')
            ->count();
        
        $userRanking = DB::table('users')
            ->join('points', 'users.user_id', '=', 'points.user_id')
            ->where('users.role', 'siswa')
            ->where('users.class_id', $userClassId)
            ->where('points.total_point', '>', $totalPoints)
            ->count() + 1;

        return response()->json([
            'success' => true,
            'data' => [
                'leaderboard' => $leaderboard,
                'statistics' => [
                    'total_quizzes' => $totalQuizzes,
                    'total_points' => $totalPoints,
                    'completed_quizzes' => $completedQuizzes,
                    'user_ranking' => $userRanking,
                ]
            ]
        ]);
    }
}

