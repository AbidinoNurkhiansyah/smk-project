<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class DashboardTest extends TestCase
{
    /**
     * Test user can view dashboard
     */
    public function test_user_can_view_dashboard()
    {
        $this->createClass();
        $this->loginAsUser();

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }

    /**
     * Test dashboard displays user information
     */
    public function test_dashboard_displays_user_information()
    {
        $this->createClass();
        $userId = $this->createUser([
            'user_name' => 'John Doe',
            'email' => 'john@example.com'
        ]);
        $this->loginAsUser($userId);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    /**
     * Test dashboard displays video statistics
     */
    public function test_dashboard_displays_video_statistics()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        // Create test videos
        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test 1',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        DB::table('videos')->insert([
            'video_id' => 2,
            'judul' => 'Video Test 2',
            'video_url' => 'https://example.com/video2',
            'class_id' => 1,
            'duration' => 15
        ]);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('totalVideos');
        $response->assertViewHas('completedVideos');
        $response->assertViewHas('inProgressVideos');
        $response->assertViewHas('notStartedVideos');
    }

    /**
     * Test dashboard displays progress for new user
     */
    public function test_dashboard_displays_zero_progress_for_new_user()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        // Create test videos
        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test 1',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('overallProgress', 0);
    }

    /**
     * Test dashboard API returns progress data
     */
    public function test_dashboard_api_returns_progress_data()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        // Create test videos
        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test 1',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        $response = $this->get('/api/dashboard/progress');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total',
            'completed',
            'in_progress',
            'not_started',
            'overall_percentage'
        ]);
    }

    /**
     * Test update video progress via API
     */
    public function test_can_update_video_progress_via_api()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        // Create test video
        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test 1',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        $response = $this->postJson('/api/video/progress', [
            'video_id' => 1,
            'progress' => 50
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'progress' => 50
        ]);

        $this->assertDatabaseHas('video_progress', [
            'user_id' => $userId,
            'video_id' => 1,
            'progress' => 50
        ]);
    }

    /**
     * Test video progress validation - max 100
     */
    public function test_video_progress_cannot_exceed_100()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test 1',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        $response = $this->postJson('/api/video/progress', [
            'video_id' => 1,
            'progress' => 150
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['progress']);
    }

    /**
     * Test video progress validation - min 0
     */
    public function test_video_progress_cannot_be_negative()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test 1',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        $response = $this->postJson('/api/video/progress', [
            'video_id' => 1,
            'progress' => -10
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['progress']);
    }

    /**
     * Test dashboard calculates overall progress correctly
     */
    public function test_dashboard_calculates_overall_progress_correctly()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        // Create 3 videos
        DB::table('videos')->insert([
            ['video_id' => 1, 'judul' => 'Video 1', 'video_url' => 'https://example.com/v1', 'class_id' => 1, 'duration' => 10],
            ['video_id' => 2, 'judul' => 'Video 2', 'video_url' => 'https://example.com/v2', 'class_id' => 1, 'duration' => 10],
            ['video_id' => 3, 'judul' => 'Video 3', 'video_url' => 'https://example.com/v3', 'class_id' => 1, 'duration' => 10],
        ]);

        // Set progress: 100%, 50%, 0%
        DB::table('video_progress')->insert([
            ['user_id' => $userId, 'video_id' => 1, 'progress' => 100, 'is_completed' => true],
            ['user_id' => $userId, 'video_id' => 2, 'progress' => 50, 'is_completed' => false],
            ['user_id' => $userId, 'video_id' => 3, 'progress' => 0, 'is_completed' => false],
        ]);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('completedVideos', 1);
        $response->assertViewHas('inProgressVideos', 1);
        $response->assertViewHas('notStartedVideos', 1);
    }
}

