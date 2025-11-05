<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherQuizController extends Controller
{
    public function index(Request $request)
    {
        $selectedClass = $request->get('class_id', 'all');
        $classes = DB::table('classes')->get();
        
        $quizzesQuery = DB::table('teacher_quizzes')
            ->join('classes', 'teacher_quizzes.class_id', '=', 'classes.class_id')
            ->join('users', 'teacher_quizzes.created_by', '=', 'users.user_id')
            ->select('teacher_quizzes.*', 'classes.class_name', 'users.user_name as created_by_name');
            
        if ($selectedClass !== 'all') {
            $quizzesQuery->where('teacher_quizzes.class_id', $selectedClass);
        }
        
        $quizzes = $quizzesQuery->orderBy('teacher_quizzes.created_at', 'desc')->get();
        
        return view('admin.teacher-quiz', compact('quizzes', 'classes', 'selectedClass'));
    }

    public function create()
    {
        $classes = DB::table('classes')->get();
        return view('admin.create-teacher-quiz', compact('classes'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'class_id' => 'required|integer|exists:classes,class_id',
                'quiz_title' => 'required|string|max:200',
                'quiz_description' => 'nullable|string|max:500',
                'total_questions' => 'required|integer|min:1|max:100',
                'time_limit' => 'required|integer|min:1|max:180',
                'points_per_question' => 'required|integer|min:1|max:100',
                'difficulty' => 'required|string|in:mudah,sedang,sulit',
                'questions' => 'required|array|min:1',
                'questions.*.question' => 'required|string|max:500',
                'questions.*.correct_answer' => 'required|string|in:A,B,C,D,E',
                'questions.*.points' => 'required|integer|min:1|max:100',
                'questions.*.options' => 'required|array|min:2|max:5',
                'questions.*.options.*' => 'required|string|max:200'
            ]);

            // Check if user is logged in
            if (!session('user_id')) {
                return redirect()->route('admin.create-teacher-quiz')->with('error', 'Session expired. Please login again.');
            }

            // Create quiz
            $quizId = DB::table('teacher_quizzes')->insertGetId([
                'class_id' => $request->class_id,
                'quiz_title' => $request->quiz_title,
                'quiz_description' => $request->quiz_description,
                'total_questions' => $request->total_questions,
                'time_limit' => $request->time_limit,
                'points_per_question' => $request->points_per_question,
                'difficulty' => $request->difficulty,
                'is_active' => true,
                'created_by' => session('user_id'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create questions and options
            foreach ($request->questions as $index => $questionData) {
                $questionId = DB::table('teacher_quiz_questions')->insertGetId([
                    'quiz_id' => $quizId,
                    'question' => $questionData['question'],
                    'correct_answer' => $questionData['correct_answer'],
                    'points' => $questionData['points'],
                    'order_number' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Create options
                $optionLabels = ['A', 'B', 'C', 'D', 'E'];
                foreach ($questionData['options'] as $optionIndex => $optionText) {
                    if (!empty($optionText)) {
                        DB::table('teacher_quiz_options')->insert([
                            'question_id' => $questionId,
                            'option_label' => $optionLabels[$optionIndex],
                            'option_text' => $optionText,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            return redirect()->route('admin.teacher-quiz')->with('success', 'Quiz berhasil dibuat!');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.create-teacher-quiz')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $quiz = DB::table('teacher_quizzes')
            ->join('classes', 'teacher_quizzes.class_id', '=', 'classes.class_id')
            ->join('users', 'teacher_quizzes.created_by', '=', 'users.user_id')
            ->select('teacher_quizzes.*', 'classes.class_name', 'users.user_name as created_by_name')
            ->where('teacher_quizzes.id', $id)
            ->first();

        if (!$quiz) {
            return redirect()->route('admin.teacher-quiz')->with('error', 'Quiz tidak ditemukan!');
        }

        $questions = DB::table('teacher_quiz_questions')
            ->where('quiz_id', $id)
            ->orderBy('order_number')
            ->get();

        // Get all options for all questions
        $questionIds = $questions->pluck('id');
        $allOptions = DB::table('teacher_quiz_options')
            ->whereIn('question_id', $questionIds)
            ->get()
            ->groupBy('question_id');

        return view('admin.show-teacher-quiz', compact('quiz', 'questions', 'allOptions'));
    }

    public function edit($id)
    {
        $quiz = DB::table('teacher_quizzes')
            ->join('classes', 'teacher_quizzes.class_id', '=', 'classes.class_id')
            ->join('users', 'teacher_quizzes.created_by', '=', 'users.user_id')
            ->select('teacher_quizzes.*', 'classes.class_name', 'users.user_name as created_by_name')
            ->where('teacher_quizzes.id', $id)
            ->first();
        
        if (!$quiz) {
            return redirect()->route('admin.teacher-quiz')->with('error', 'Quiz tidak ditemukan!');
        }

        $classes = DB::table('classes')->get();
        
        $questions = DB::table('teacher_quiz_questions')
            ->where('quiz_id', $id)
            ->orderBy('order_number')
            ->get();

        $questionsWithOptions = [];
        foreach ($questions as $question) {
            $options = DB::table('teacher_quiz_options')
                ->where('question_id', $question->id)
                ->orderBy('option_label')
                ->get();
            
            $questionsWithOptions[] = [
                'question' => $question,
                'options' => $options
            ];
        }

        return view('admin.edit-teacher-quiz', compact('quiz', 'classes', 'questionsWithOptions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required|integer|exists:classes,class_id',
            'quiz_title' => 'required|string|max:200',
            'quiz_description' => 'nullable|string|max:500',
            'total_questions' => 'required|integer|min:1|max:100',
            'time_limit' => 'required|integer|min:1|max:180',
            'points_per_question' => 'required|integer|min:1|max:100',
            'difficulty' => 'required|string|in:mudah,sedang,sulit',
            'is_active' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string|max:500',
            'questions.*.correct_answer' => 'required|string|in:A,B,C,D,E',
            'questions.*.points' => 'required|integer|min:1|max:100',
            'questions.*.options' => 'required|array|min:2|max:5',
            'questions.*.options.*' => 'required|string|max:200'
        ]);

        // Update quiz
        DB::table('teacher_quizzes')
            ->where('id', $id)
            ->update([
                'class_id' => $request->class_id,
                'quiz_title' => $request->quiz_title,
                'quiz_description' => $request->quiz_description,
                'total_questions' => $request->total_questions,
                'time_limit' => $request->time_limit,
                'points_per_question' => $request->points_per_question,
                'difficulty' => $request->difficulty,
                'is_active' => $request->has('is_active'),
                'updated_at' => now()
            ]);

        // Delete existing questions and options
        $questionIds = DB::table('teacher_quiz_questions')
            ->where('quiz_id', $id)
            ->pluck('id');
            
        DB::table('teacher_quiz_options')
            ->whereIn('question_id', $questionIds)
            ->delete();
            
        DB::table('teacher_quiz_questions')
            ->where('quiz_id', $id)
            ->delete();

        // Create new questions and options
        foreach ($request->questions as $index => $questionData) {
            $questionId = DB::table('teacher_quiz_questions')->insertGetId([
                'quiz_id' => $id,
                'question' => $questionData['question'],
                'correct_answer' => $questionData['correct_answer'],
                'points' => $questionData['points'],
                'order_number' => $index + 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create options
            $optionLabels = ['A', 'B', 'C', 'D', 'E'];
            foreach ($questionData['options'] as $optionIndex => $optionText) {
                if (!empty($optionText)) {
                    DB::table('teacher_quiz_options')->insert([
                        'question_id' => $questionId,
                        'option_label' => $optionLabels[$optionIndex],
                        'option_text' => $optionText,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

        return redirect()->route('admin.teacher-quiz')->with('success', 'Quiz berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Delete options first
        $questionIds = DB::table('teacher_quiz_questions')
            ->where('quiz_id', $id)
            ->pluck('id');
            
        DB::table('teacher_quiz_options')
            ->whereIn('question_id', $questionIds)
            ->delete();
            
        // Delete questions
        DB::table('teacher_quiz_questions')
            ->where('quiz_id', $id)
            ->delete();
            
        // Delete quiz
        DB::table('teacher_quizzes')
            ->where('id', $id)
            ->delete();

        return redirect()->route('admin.teacher-quiz')->with('success', 'Quiz berhasil dihapus!');
    }
}