<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ApiTest extends TestCase
{
    /**
     * Test API status endpoint
     */
    public function test_api_status_endpoint()
    {
        $response = $this->getJson('/api/status');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'API is running'
        ]);
    }

    /**
     * Test API login success
     */
    public function test_api_login_success()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'token',
                'user' => [
                    'user_id',
                    'user_name',
                    'email',
                    'role',
                    'class_id'
                ]
            ]
        ]);

        $this->assertNotNull($response->json('data.token'));
    }

    /**
     * Test API login fails with wrong password
     */
    public function test_api_login_fails_with_wrong_password()
    {
        $this->createClass();
        $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false
        ]);
    }

    /**
     * Test API register success
     */
    public function test_api_register_success()
    {
        $this->createClass();

        $response = $this->postJson('/api/register', [
            'user_name' => 'API User',
            'email' => 'apiuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'class_id' => 1
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'token',
                'user'
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'apiuser@example.com',
            'user_name' => 'API User'
        ]);
    }

    /**
     * Test API register validation
     */
    public function test_api_register_validation()
    {
        $this->createClass();

        $response = $this->postJson('/api/register', [
            'user_name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different',
            'class_id' => 999
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['user_name', 'email', 'password', 'class_id']);
    }

    /**
     * Test API get user info (me)
     */
    public function test_api_get_user_info()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        // Login to get token
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->getJson('/api/me');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'user_id',
                'user_name',
                'email',
                'role',
                'class_id'
            ]
        ]);
    }

    /**
     * Test API get dashboard
     */
    public function test_api_get_dashboard()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->getJson('/api/dashboard');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'user',
                'statistics',
                'videos'
            ]
        ]);
    }

    /**
     * Test API get videos list
     */
    public function test_api_get_videos_list()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        // Create videos
        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video API Test',
            'video_url' => 'https://example.com/video',
            'class_id' => 1,
            'duration' => 10
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->getJson('/api/videos');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data'
        ]);
    }

    /**
     * Test API get video detail
     */
    public function test_api_get_video_detail()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video API Test',
            'video_url' => 'https://example.com/video',
            'class_id' => 1,
            'duration' => 10
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->getJson('/api/videos/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data'
        ]);
    }

    /**
     * Test API update video progress
     */
    public function test_api_update_video_progress()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video API Test',
            'video_url' => 'https://example.com/video',
            'class_id' => 1,
            'duration' => 10
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson('/api/videos/1/progress', [
            'progress' => 75
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'progress',
                'is_completed',
                'points_earned'
            ]
        ]);

        $this->assertDatabaseHas('video_progress', [
            'user_id' => $userId,
            'video_id' => 1,
            'progress' => 75
        ]);
    }

    /**
     * Test API get quizzes list
     */
    public function test_api_get_quizzes_list()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        DB::table('teacher_quizzes')->insert([
            'class_id' => 1,
            'quiz_title' => 'Quiz API Test',
            'quiz_description' => 'Test',
            'total_questions' => 5,
            'time_limit' => 30,
            'is_active' => true,
            'created_by' => $userId
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->getJson('/api/quizzes');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data'
        ]);
    }

    /**
     * Test API get quiz detail
     */
    public function test_api_get_quiz_detail()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        $quizId = DB::table('teacher_quizzes')->insertGetId([
            'class_id' => 1,
            'quiz_title' => 'Quiz API Test',
            'quiz_description' => 'Test',
            'total_questions' => 2,
            'time_limit' => 30,
            'is_active' => true,
            'created_by' => $userId
        ]);

        $questionId = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Test Question?',
            'correct_answer' => 'A',
            'order_number' => 1,
            'points' => 10
        ]);

        DB::table('teacher_quiz_options')->insert([
            ['question_id' => $questionId, 'option_label' => 'A', 'option_text' => 'Answer A'],
            ['question_id' => $questionId, 'option_label' => 'B', 'option_text' => 'Answer B'],
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->getJson("/api/quizzes/{$quizId}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'quiz',
                'questions'
            ]
        ]);
    }

    /**
     * Test API submit quiz
     */
    public function test_api_submit_quiz()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        DB::table('points')->insert([
            'point_id' => 1,
            'user_id' => $userId,
            'total_point' => 0
        ]);

        $quizId = DB::table('teacher_quizzes')->insertGetId([
            'class_id' => 1,
            'quiz_title' => 'Quiz API Test',
            'quiz_description' => 'Test',
            'total_questions' => 2,
            'time_limit' => 30,
            'is_active' => true,
            'created_by' => $userId
        ]);

        $question1Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Q1',
            'correct_answer' => 'A',
            'order_number' => 1,
            'points' => 10
        ]);

        $question2Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Q2',
            'correct_answer' => 'B',
            'order_number' => 2,
            'points' => 10
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson("/api/quizzes/{$quizId}/submit", [
            'answers' => [
                (string)$question1Id => 'A',
                (string)$question2Id => 'B'
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'total_score',
                'correct_answers',
                'total_questions',
                'percentage',
                'grade',
                'points_earned'
            ]
        ]);
    }

    /**
     * Test API get leaderboard
     */
    public function test_api_get_leaderboard()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        DB::table('points')->insert([
            'point_id' => 1,
            'user_id' => $userId,
            'total_point' => 100
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->getJson('/api/leaderboard');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data'
        ]);
    }

    /**
     * Test API logout
     */
    public function test_api_logout()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Logout berhasil.'
        ]);

        // Token should be null after logout
        $user = DB::table('users')->where('user_id', $userId)->first();
        $this->assertNull($user->api_token);
    }

    /**
     * Test API protected routes require authentication
     */
    public function test_api_protected_routes_require_authentication()
    {
        $response = $this->getJson('/api/dashboard');
        $response->assertStatus(401);

        $response = $this->getJson('/api/videos');
        $response->assertStatus(401);

        $response = $this->getJson('/api/quizzes');
        $response->assertStatus(401);
    }

    /**
     * Test API returns 404 for non-existent resource
     */
    public function test_api_returns_404_for_nonexistent_resource()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'api@example.com',
            'password' => Hash::make('password123')
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->getJson('/api/videos/99999');

        $response->assertStatus(404);
    }
}

