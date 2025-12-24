<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class VideoTest extends TestCase
{
    /**
     * Test user can view video list page
     */
    public function test_user_can_view_video_list_page()
    {
        $this->createClass();
        $this->loginAsUser();

        $response = $this->get('/video-pembelajaran');

        $response->assertStatus(200);
        $response->assertViewIs('video-pembelajaran');
    }

    /**
     * Test video list displays videos for user's class
     */
    public function test_video_list_displays_videos_for_user_class()
    {
        $this->createClass();
        $userId = $this->createUser(['class_id' => 1]);
        $this->loginAsUser($userId);

        // Create videos for class 1
        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Kelas 1',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        // Create video for different class
        DB::table('videos')->insert([
            'video_id' => 2,
            'judul' => 'Video Kelas 2',
            'video_url' => 'https://example.com/video2',
            'class_id' => 2,
            'duration' => 10
        ]);

        $response = $this->get('/video-pembelajaran');

        $response->assertStatus(200);
        $response->assertViewHas('videoData');
        
        $videoData = $response->viewData('videoData');
        $this->assertCount(1, $videoData);
        $this->assertEquals('Video Kelas 1', $videoData[0]['title']);
    }

    /**
     * Test user can view video player
     */
    public function test_user_can_view_video_player()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        $response = $this->get('/video/1');

        $response->assertStatus(200);
        $response->assertViewIs('video-player');
        $response->assertViewHas('video');
    }

    /**
     * Test video player shows progress if video was watched
     */
    public function test_video_player_shows_progress_if_watched()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        // Set progress
        DB::table('video_progress')->insert([
            'user_id' => $userId,
            'video_id' => 1,
            'progress' => 50,
            'is_completed' => false
        ]);

        $response = $this->get('/video/1');

        $response->assertStatus(200);
        $response->assertViewHas('progressPercentage', 50);
    }

    /**
     * Test user can update video progress
     */
    public function test_user_can_update_video_progress()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        $response = $this->post('/video/progress', [
            'video_id' => 1,
            'progress' => 75
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'progress' => 75,
            'is_completed' => false
        ]);

        $this->assertDatabaseHas('video_progress', [
            'user_id' => $userId,
            'video_id' => 1,
            'progress' => 75
        ]);
    }

    /**
     * Test video completion awards points
     */
    public function test_video_completion_awards_points()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        // Create points entry
        DB::table('points')->insert([
            'point_id' => 1,
            'user_id' => $userId,
            'total_point' => 0
        ]);

        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        // Complete video
        $response = $this->post('/video/progress', [
            'video_id' => 1,
            'progress' => 100
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'progress' => 100,
            'is_completed' => true
        ]);

        // Check points awarded
        $points = DB::table('points')->where('user_id', $userId)->value('total_point');
        $this->assertEquals(10, $points);
    }

    /**
     * Test video completion only awards points once
     */
    public function test_video_completion_awards_points_only_once()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        DB::table('points')->insert([
            'point_id' => 1,
            'user_id' => $userId,
            'total_point' => 0
        ]);

        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        // Complete video first time
        $this->post('/video/progress', [
            'video_id' => 1,
            'progress' => 100
        ]);

        $pointsAfterFirst = DB::table('points')->where('user_id', $userId)->value('total_point');
        $this->assertEquals(10, $pointsAfterFirst);

        // Update progress again (should not award more points)
        $this->post('/video/progress', [
            'video_id' => 1,
            'progress' => 50
        ]);

        $this->post('/video/progress', [
            'video_id' => 1,
            'progress' => 100
        ]);

        $pointsAfterSecond = DB::table('points')->where('user_id', $userId)->value('total_point');
        $this->assertEquals(10, $pointsAfterSecond); // Should still be 10
    }

    /**
     * Test video progress validation
     */
    public function test_video_progress_validation()
    {
        $this->createClass();
        $this->loginAsUser();

        DB::table('videos')->insert([
            'video_id' => 1,
            'judul' => 'Video Test',
            'video_url' => 'https://example.com/video1',
            'class_id' => 1,
            'duration' => 10
        ]);

        // Test missing video_id
        $response = $this->post('/video/progress', [
            'progress' => 50
        ]);
        $response->assertStatus(422);

        // Test missing progress
        $response = $this->post('/video/progress', [
            'video_id' => 1
        ]);
        $response->assertStatus(422);

        // Test invalid video_id
        $response = $this->post('/video/progress', [
            'video_id' => 999,
            'progress' => 50
        ]);
        $response->assertStatus(422);
    }

    /**
     * Test video not found redirects
     */
    public function test_video_not_found_redirects()
    {
        $this->createClass();
        $this->loginAsUser();

        $response = $this->get('/video/999');

        $response->assertRedirect('/video-pembelajaran');
        $response->assertSessionHas('error');
    }
}

