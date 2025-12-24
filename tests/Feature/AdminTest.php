<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminTest extends TestCase
{
    /**
     * Test admin can view admin dashboard
     */
    public function test_admin_can_view_admin_dashboard()
    {
        $this->createClass();
        $this->loginAsAdmin();

        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    /**
     * Test student can access admin dashboard (no role check in middleware)
     * Note: In real application, you might want to add role check middleware
     */
    public function test_student_can_access_admin_dashboard()
    {
        $this->createClass();
        $this->loginAsUser();

        $response = $this->get('/admin');

        // Currently, students can access admin pages because middleware only checks login
        // In production, you should add role-based middleware
        $response->assertStatus(200);
    }

    /**
     * Test admin can view videos list
     */
    public function test_admin_can_view_videos_list()
    {
        $this->createClass();
        $this->loginAsAdmin();

        $response = $this->get('/admin/videos');

        $response->assertStatus(200);
        $response->assertViewIs('admin.videos');
    }

    /**
     * Test admin can add video
     */
    public function test_admin_can_add_video()
    {
        $this->createClass();
        $this->loginAsAdmin();

        Storage::fake('public');

        $response = $this->post('/admin/videos/add', [
            'judul' => 'Video Baru',
            'deskripsi' => 'Deskripsi video',
            'video_url' => 'https://example.com/video',
            'class_id' => 1,
            'duration' => '30.0'
        ]);

        $response->assertRedirect('/admin/videos');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('videos', [
            'judul' => 'Video Baru',
            'class_id' => 1
        ]);
    }

    /**
     * Test admin can edit video
     */
    public function test_admin_can_edit_video()
    {
        $this->createClass();
        $this->loginAsAdmin();

        $videoId = DB::table('videos')->insertGetId([
            'video_id' => 1,
            'judul' => 'Video Lama',
            'video_url' => 'https://example.com/video',
            'class_id' => 1,
            'duration' => 30
        ]);

        $response = $this->put("/admin/videos/{$videoId}/update", [
            'judul' => 'Video Updated',
            'deskripsi' => 'Deskripsi baru',
            'video_url' => 'https://example.com/video-new',
            'class_id' => 1,
            'duration' => '45.0'
        ]);

        $response->assertRedirect('/admin/videos');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('videos', [
            'video_id' => $videoId,
            'judul' => 'Video Updated'
        ]);
    }

    /**
     * Test admin can delete video
     */
    public function test_admin_can_delete_video()
    {
        $this->createClass();
        $this->loginAsAdmin();

        $videoId = DB::table('videos')->insertGetId([
            'video_id' => 1,
            'judul' => 'Video Test',
            'video_url' => 'https://example.com/video',
            'class_id' => 1,
            'duration' => 30
        ]);

        $response = $this->delete("/admin/videos/{$videoId}/delete");

        $response->assertRedirect('/admin/videos');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('videos', [
            'video_id' => $videoId
        ]);
    }

    /**
     * Test admin can view students list
     */
    public function test_admin_can_view_students_list()
    {
        $this->createClass();
        $this->loginAsAdmin();

        $response = $this->get('/admin/students');

        $response->assertStatus(200);
        $response->assertViewIs('admin.students');
    }

    /**
     * Test admin can view student progress
     */
    public function test_admin_can_view_student_progress()
    {
        $this->createClass();
        $this->loginAsAdmin();

        $userId = $this->createUser();

        // Create video and progress
        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test',
            'video_url' => 'https://example.com/video',
            'class_id' => 1,
            'duration' => 30
        ]);

        DB::table('video_progress')->insert([
            'user_id' => $userId,
            'video_id' => 1,
            'progress' => 50,
            'is_completed' => false
        ]);

        $response = $this->get("/admin/students/{$userId}/progress");

        $response->assertStatus(200);
        $response->assertViewIs('admin.student-progress');
    }

    /**
     * Test admin can delete student
     */
    public function test_admin_can_delete_student()
    {
        $this->createClass();
        $this->loginAsAdmin();

        $userId = $this->createUser();

        $response = $this->delete("/admin/students/{$userId}/delete");

        $response->assertRedirect('/admin/students');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'user_id' => $userId
        ]);
    }

    /**
     * Test admin can view analytics
     */
    public function test_admin_can_view_analytics()
    {
        $this->createClass();
        $this->loginAsAdmin();

        $response = $this->get('/admin/analytics');

        $response->assertStatus(200);
        $response->assertViewIs('admin.analytics');
    }

    /**
     * Test admin can view leaderboard
     */
    public function test_admin_can_view_leaderboard()
    {
        $this->createClass();
        $this->loginAsAdmin();

        $response = $this->get('/admin/leaderboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.leaderboard');
    }

    /**
     * Test admin can view teacher quiz list
     */
    public function test_admin_can_view_teacher_quiz_list()
    {
        $this->createClass();
        $adminId = $this->loginAsAdmin();

        $response = $this->get('/admin/teacher-quiz');

        $response->assertStatus(200);
        $response->assertViewIs('admin.teacher-quiz');
    }

    /**
     * Test admin can create quiz
     */
    public function test_admin_can_create_quiz()
    {
        $this->createClass();
        $adminId = $this->loginAsAdmin();

        $response = $this->post('/admin/teacher-quiz', [
            'quiz_title' => 'Quiz Baru',
            'quiz_description' => 'Deskripsi quiz',
            'class_id' => 1,
            'time_limit' => 30,
            'difficulty' => 'mudah',
            'total_questions' => 1,
            'points_per_question' => 10,
            'questions' => [
                [
                    'question' => 'Pertanyaan test?',
                    'correct_answer' => 'A',
                    'points' => 10,
                    'options' => [
                        'Jawaban A',
                        'Jawaban B',
                        'Jawaban C',
                        'Jawaban D'
                    ]
                ]
            ]
        ]);

        $response->assertRedirect('/admin/teacher-quiz');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('teacher_quizzes', [
            'quiz_title' => 'Quiz Baru',
            'class_id' => 1
        ]);
    }

    /**
     * Test admin can edit quiz
     */
    public function test_admin_can_edit_quiz()
    {
        $this->createClass();
        $adminId = $this->loginAsAdmin();

        DB::table('teacher_quizzes')->insert([
            'class_id' => 1,
            'quiz_title' => 'Quiz Lama',
            'quiz_description' => 'Deskripsi lama',
            'total_questions' => 5,
            'time_limit' => 30,
            'points_per_question' => 10,
            'difficulty' => 'mudah',
            'is_active' => true,
            'created_by' => $adminId->user_id ?? $adminId,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $quizId = DB::table('teacher_quizzes')
            ->where('quiz_title', 'Quiz Lama')
            ->value('id');

        $response = $this->put("/admin/teacher-quiz/{$quizId}", [
            'quiz_title' => 'Quiz Updated',
            'quiz_description' => 'Deskripsi baru',
            'class_id' => 1,
            'time_limit' => 45,
            'difficulty' => 'sedang',
            'total_questions' => 1,
            'points_per_question' => 10,
            'questions' => [
                [
                    'question' => 'Pertanyaan updated?',
                    'correct_answer' => 'B',
                    'points' => 10,
                    'options' => [
                        'Jawaban A',
                        'Jawaban B',
                        'Jawaban C',
                        'Jawaban D'
                    ]
                ]
            ]
        ]);

        $response->assertRedirect('/admin/teacher-quiz');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('teacher_quizzes', [
            'id' => $quizId,
            'quiz_title' => 'Quiz Updated'
        ]);
    }

    /**
     * Test admin can delete quiz
     */
    public function test_admin_can_delete_quiz()
    {
        $this->createClass();
        $adminId = $this->loginAsAdmin();

        DB::table('teacher_quizzes')->insert([
            'class_id' => 1,
            'quiz_title' => 'Quiz Test',
            'quiz_description' => 'Test',
            'total_questions' => 5,
            'time_limit' => 30,
            'points_per_question' => 10,
            'difficulty' => 'mudah',
            'is_active' => true,
            'created_by' => $adminId->user_id ?? $adminId,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $quizId = DB::table('teacher_quizzes')
            ->where('quiz_title', 'Quiz Test')
            ->value('id');

        $response = $this->delete("/admin/teacher-quiz/{$quizId}");

        $response->assertRedirect('/admin/teacher-quiz');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('teacher_quizzes', [
            'id' => $quizId
        ]);
    }

    /**
     * Test admin can view quiz analytics
     */
    public function test_admin_can_view_quiz_analytics()
    {
        $this->createClass();
        $this->loginAsAdmin();

        $response = $this->get('/admin/quiz-analytics');

        $response->assertStatus(200);
        $response->assertViewIs('admin.quiz-analytics');
    }
}

