<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuizAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get filter parameters
            $classId = $request->get('class_id', 'all');
            $quizId = $request->get('quiz_id', 'all');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            // Get all classes for filter
            $classes = DB::table('classes')
                ->orderBy('class_name')
                ->get();

            // Get all quizzes for filter
            $quizzes = DB::table('teacher_quizzes')
                ->join('classes', 'teacher_quizzes.class_id', '=', 'classes.class_id')
                ->select('teacher_quizzes.*', 'classes.class_name')
                ->orderBy('teacher_quizzes.created_at', 'desc')
                ->get();

            // Initialize empty collections for when no data exists
            $quizResults = collect();
            $quizSummary = collect();

            // Check if teacher_quiz_answers table has data
            $hasData = DB::table('teacher_quiz_answers')->exists();
            
            if ($hasData) {
                // Build query for quiz results
                $query = DB::table('teacher_quiz_answers as tqa')
                    ->join('users', 'tqa.user_id', '=', 'users.user_id')
                    ->join('classes', 'users.class_id', '=', 'classes.class_id')
                    ->join('teacher_quiz_questions as tqq', 'tqa.question_id', '=', 'tqq.id')
                    ->join('teacher_quizzes as tq', 'tqq.quiz_id', '=', 'tq.id')
                    ->select(
                        'tqa.*',
                        'users.user_name',
                        'users.email',
                        'classes.class_name',
                        'tq.quiz_title',
                        'tqq.question as question_text',
                        'tqa.user_answer as option_text',
                        'tqa.is_correct',
                        'tqa.answered_at'
                    );

                // Apply filters
                if ($classId !== 'all') {
                    $query->where('users.class_id', $classId);
                }

                if ($quizId !== 'all') {
                    $query->where('tq.id', $quizId);
                }

                if ($dateFrom) {
                    $query->whereDate('tqa.answered_at', '>=', $dateFrom);
                }

                if ($dateTo) {
                    $query->whereDate('tqa.answered_at', '<=', $dateTo);
                }

                $quizResults = $query->orderBy('tqa.answered_at', 'desc')->get();

                // Group results by user and quiz for summary
                $quizSummary = $quizResults->groupBy(function ($item) {
                    return $item->user_id . '_' . $item->quiz_id;
                })->map(function ($group) {
                    $first = $group->first();
                    $totalQuestions = $group->count();
                    $correctAnswers = $group->where('is_correct', 1)->count();
                    $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

                    return [
                        'user_id' => $first->user_id,
                        'user_name' => $first->user_name,
                        'email' => $first->email,
                        'class_name' => $first->class_name,
                        'quiz_id' => $first->quiz_id,
                        'quiz_title' => $first->quiz_title,
                        'total_questions' => $totalQuestions,
                        'correct_answers' => $correctAnswers,
                        'score' => $score,
                        'answered_at' => $first->answered_at,
                        'details' => $group->toArray()
                    ];
                })->values();
            }

            // Get statistics
            $stats = [
                'total_attempts' => $quizSummary->count(),
                'average_score' => $quizSummary->count() > 0 ? $quizSummary->avg('score') : 0,
                'highest_score' => $quizSummary->count() > 0 ? $quizSummary->max('score') : 0,
                'lowest_score' => $quizSummary->count() > 0 ? $quizSummary->min('score') : 0,
                'total_students' => $quizSummary->count() > 0 ? $quizSummary->unique('user_id')->count() : 0,
                'total_quizzes' => $quizSummary->count() > 0 ? $quizSummary->unique('quiz_id')->count() : 0
            ];

            return view('admin.quiz-analytics', compact(
                'quizResults',
                'quizSummary',
                'classes',
                'quizzes',
                'stats',
                'classId',
                'quizId',
                'dateFrom',
                'dateTo'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function detail($userId, $quizId)
    {
        try {
            // Get user info
            $user = DB::table('users')
                ->join('classes', 'users.class_id', '=', 'classes.class_id')
                ->where('users.user_id', $userId)
                ->select('users.*', 'classes.class_name')
                ->first();

            if (!$user) {
                return redirect()->back()->with('error', 'Siswa tidak ditemukan');
            }

            // Get quiz info
            $quiz = DB::table('teacher_quizzes')
                ->join('classes', 'teacher_quizzes.class_id', '=', 'classes.class_id')
                ->where('teacher_quizzes.id', $quizId)
                ->select('teacher_quizzes.*', 'classes.class_name')
                ->first();

            if (!$quiz) {
                return redirect()->back()->with('error', 'Quiz tidak ditemukan');
            }

            // Get detailed answers
            $answers = DB::table('teacher_quiz_answers as tqa')
                ->join('teacher_quiz_questions as tqq', 'tqa.question_id', '=', 'tqq.id')
                ->where('tqa.user_id', $userId)
                ->where('tqa.quiz_id', $quizId)
                ->select(
                    'tqq.question as question_text',
                    'tqq.id as question_id',
                    'tqa.user_answer as selected_option',
                    'tqa.is_correct as is_selected_correct',
                    'tqa.answered_at'
                )
                ->orderBy('tqq.id')
                ->get();

            // Get all questions for this quiz to show all options
            $questions = DB::table('teacher_quiz_questions as tqq')
                ->where('tqq.quiz_id', $quizId)
                ->select(
                    'tqq.id as question_id',
                    'tqq.question as question_text',
                    'tqq.correct_answer'
                )
                ->orderBy('tqq.id')
                ->get();

            // Calculate score
            $totalQuestions = $questions->count();
            $correctAnswers = $answers->where('is_selected_correct', 1)->count();
            $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

            return view('admin.quiz-detail', compact(
                'user',
                'quiz',
                'answers',
                'questions',
                'score',
                'totalQuestions',
                'correctAnswers'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
