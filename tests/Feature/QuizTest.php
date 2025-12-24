<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class QuizTest extends TestCase
{
    /**
     * Test user can view quiz list page
     */
    public function test_user_can_view_quiz_list_page()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        // Create quiz
        DB::table('teacher_quizzes')->insert([
            'class_id' => 1,
            'quiz_title' => 'Quiz Test',
            'quiz_description' => 'Test Description',
            'total_questions' => 5,
            'time_limit' => 30,
            'is_active' => true,
            'created_by' => $userId
        ]);

        $response = $this->get('/game');

        $response->assertStatus(200);
        $response->assertViewIs('game.index');
    }

    /**
     * Test quiz list shows only active quizzes for user's class
     */
    public function test_quiz_list_shows_only_active_quizzes_for_user_class()
    {
        $this->createClass();
        $userId = $this->createUser(['class_id' => 1]);
        $this->loginAsUser($userId);

        // Create active quiz for class 1
        DB::table('teacher_quizzes')->insert([
            'class_id' => 1,
            'quiz_title' => 'Quiz Kelas 1',
            'quiz_description' => 'Test',
            'total_questions' => 5,
            'time_limit' => 30,
            'is_active' => true,
            'created_by' => $userId
        ]);

        // Create inactive quiz
        DB::table('teacher_quizzes')->insert([
            'class_id' => 1,
            'quiz_title' => 'Quiz Inactive',
            'quiz_description' => 'Test',
            'total_questions' => 5,
            'time_limit' => 30,
            'is_active' => false,
            'created_by' => $userId
        ]);

        // Create quiz for different class
        DB::table('teacher_quizzes')->insert([
            'class_id' => 2,
            'quiz_title' => 'Quiz Kelas 2',
            'quiz_description' => 'Test',
            'total_questions' => 5,
            'time_limit' => 30,
            'is_active' => true,
            'created_by' => $userId
        ]);

        $response = $this->get('/game');

        $response->assertStatus(200);
        $quizzes = $response->viewData('quizzes');
        $this->assertCount(1, $quizzes);
        $this->assertEquals('Quiz Kelas 1', $quizzes[0]->quiz_title);
    }

    /**
     * Test user can start quiz
     */
    public function test_user_can_start_quiz()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        $quizId = DB::table('teacher_quizzes')->insertGetId([
            'class_id' => 1,
            'quiz_title' => 'Quiz Test',
            'quiz_description' => 'Test',
            'total_questions' => 2,
            'time_limit' => 30,
            'is_active' => true,
            'created_by' => $userId
        ]);

        // Create questions
        $question1Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Question 1?',
            'correct_answer' => 'A',
            'order_number' => 1,
            'points' => 10
        ]);

        $question2Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Question 2?',
            'correct_answer' => 'B',
            'order_number' => 2,
            'points' => 10
        ]);

        // Create options
        DB::table('teacher_quiz_options')->insert([
            ['question_id' => $question1Id, 'option_label' => 'A', 'option_text' => 'Answer A'],
            ['question_id' => $question1Id, 'option_label' => 'B', 'option_text' => 'Answer B'],
            ['question_id' => $question2Id, 'option_label' => 'A', 'option_text' => 'Answer A'],
            ['question_id' => $question2Id, 'option_label' => 'B', 'option_text' => 'Answer B'],
        ]);

        $response = $this->get("/game/play/{$quizId}");

        $response->assertStatus(200);
        $response->assertViewIs('game.play');
        $response->assertViewHas('quiz');
        $response->assertViewHas('questions');
    }

    /**
     * Test user can submit quiz
     */
    public function test_user_can_submit_quiz()
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

        $quizId = DB::table('teacher_quizzes')->insertGetId([
            'class_id' => 1,
            'quiz_title' => 'Quiz Test',
            'quiz_description' => 'Test',
            'total_questions' => 2,
            'time_limit' => 30,
            'is_active' => true,
            'created_by' => $userId
        ]);

        $question1Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Question 1?',
            'correct_answer' => 'A',
            'order_number' => 1,
            'points' => 10
        ]);

        $question2Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Question 2?',
            'correct_answer' => 'B',
            'order_number' => 2,
            'points' => 10
        ]);

        $response = $this->post("/game/submit/{$quizId}", [
            'answers' => [
                $question1Id => 'A', // Correct
                $question2Id => 'B'  // Correct
            ]
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('game.result');
        $response->assertViewHas('correctAnswers', 2);
        $response->assertViewHas('totalQuestions', 2);
        $response->assertViewHas('percentage', 100.0);
    }

    /**
     * Test quiz submission calculates score correctly
     */
    public function test_quiz_submission_calculates_score_correctly()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        DB::table('points')->insert([
            'point_id' => 1,
            'user_id' => $userId,
            'total_point' => 0
        ]);

        $quizId = DB::table('teacher_quizzes')->insertGetId([
            'class_id' => 1,
            'quiz_title' => 'Quiz Test',
            'quiz_description' => 'Test',
            'total_questions' => 4,
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

        $question3Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Q3',
            'correct_answer' => 'C',
            'order_number' => 3,
            'points' => 10
        ]);

        $question4Id = DB::table('teacher_quiz_questions')->insertGetId([
            'quiz_id' => $quizId,
            'question' => 'Q4',
            'correct_answer' => 'D',
            'order_number' => 4,
            'points' => 10
        ]);

        // Answer 3 out of 4 correctly (75%)
        $response = $this->post("/game/submit/{$quizId}", [
            'answers' => [
                $question1Id => 'A', // Correct
                $question2Id => 'B', // Correct
                $question3Id => 'C', // Correct
                $question4Id => 'A'  // Wrong
            ]
        ]);

        $response->assertStatus(200);
        $response->assertViewHas('correctAnswers', 3);
        $response->assertViewHas('totalQuestions', 4);
        $response->assertViewHas('percentage', 75.0);
    }

    /**
     * Test quiz awards points on first attempt
     */
    public function test_quiz_awards_points_on_first_attempt()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        DB::table('points')->insert([
            'point_id' => 1,
            'user_id' => $userId,
            'total_point' => 0
        ]);

        $quizId = DB::table('teacher_quizzes')->insertGetId([
            'class_id' => 1,
            'quiz_title' => 'Quiz Test',
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

        // Submit quiz with 100% score
        $this->post("/game/submit/{$quizId}", [
            'answers' => [
                $question1Id => 'A',
                $question2Id => 'B'
            ]
        ]);

        // Check points awarded
        $points = DB::table('points')->where('user_id', $userId)->value('total_point');
        $this->assertGreaterThan(0, $points);
    }

    /**
     * Test quiz does not award points on retake
     */
    public function test_quiz_does_not_award_points_on_retake()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        DB::table('points')->insert([
            'point_id' => 1,
            'user_id' => $userId,
            'total_point' => 0
        ]);

        $quizId = DB::table('teacher_quizzes')->insertGetId([
            'class_id' => 1,
            'quiz_title' => 'Quiz Test',
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

        // First attempt
        $this->post("/game/submit/{$quizId}", [
            'answers' => [
                $question1Id => 'A',
                $question2Id => 'B'
            ]
        ]);

        $pointsAfterFirst = DB::table('points')->where('user_id', $userId)->value('total_point');

        // Second attempt (retake)
        $this->post("/game/submit/{$quizId}", [
            'answers' => [
                $question1Id => 'A',
                $question2Id => 'B'
            ]
        ]);

        $pointsAfterSecond = DB::table('points')->where('user_id', $userId)->value('total_point');
        
        // Points should not increase on retake
        $this->assertEquals($pointsAfterFirst, $pointsAfterSecond);
    }

    /**
     * Test user can view leaderboard
     */
    public function test_user_can_view_leaderboard()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        // Create other users with points
        $user2Id = $this->createUser([
            'user_name' => 'User 2',
            'email' => 'user2@example.com',
            'class_id' => 1
        ]);

        $user3Id = $this->createUser([
            'user_name' => 'User 3',
            'email' => 'user3@example.com',
            'class_id' => 1
        ]);

        DB::table('points')->insert([
            ['point_id' => 1, 'user_id' => $userId, 'total_point' => 50],
            ['point_id' => 2, 'user_id' => $user2Id, 'total_point' => 100],
            ['point_id' => 3, 'user_id' => $user3Id, 'total_point' => 75],
        ]);

        $response = $this->get('/game/leaderboard');

        $response->assertStatus(200);
        $response->assertViewIs('game.leaderboard');
        $response->assertViewHas('leaderboard');
        
        $leaderboard = $response->viewData('leaderboard');
        $this->assertCount(3, $leaderboard);
        // Should be sorted by points descending
        $this->assertEquals(100, $leaderboard[0]->total_point);
        $this->assertEquals(75, $leaderboard[1]->total_point);
        $this->assertEquals(50, $leaderboard[2]->total_point);
    }

    /**
     * Test leaderboard only shows users from same class
     */
    public function test_leaderboard_only_shows_users_from_same_class()
    {
        $this->createClass();
        
        // Create class 2
        DB::table('classes')->insert([
            'class_id' => 2,
            'class_name' => 'Kelas XI'
        ]);

        $userId = $this->createUser(['class_id' => 1]);
        $this->loginAsUser($userId);

        $user2Id = $this->createUser([
            'user_name' => 'User 2',
            'email' => 'user2@example.com',
            'class_id' => 1
        ]);

        $user3Id = $this->createUser([
            'user_name' => 'User 3',
            'email' => 'user3@example.com',
            'class_id' => 2 // Different class
        ]);

        DB::table('points')->insert([
            ['point_id' => 1, 'user_id' => $userId, 'total_point' => 50],
            ['point_id' => 2, 'user_id' => $user2Id, 'total_point' => 100],
            ['point_id' => 3, 'user_id' => $user3Id, 'total_point' => 75],
        ]);

        $response = $this->get('/game/leaderboard');

        $leaderboard = $response->viewData('leaderboard');
        // Should only show users from class 1
        $this->assertCount(2, $leaderboard);
        foreach ($leaderboard as $entry) {
            $this->assertEquals(1, $entry->class_id ?? 1);
        }
    }

    /**
     * Test quiz validation requires answers
     */
    public function test_quiz_submission_requires_answers()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        $quizId = DB::table('teacher_quizzes')->insertGetId([
            'class_id' => 1,
            'quiz_title' => 'Quiz Test',
            'quiz_description' => 'Test',
            'total_questions' => 2,
            'time_limit' => 30,
            'is_active' => true,
            'created_by' => $userId
        ]);

        $response = $this->post("/game/submit/{$quizId}", []);

        $response->assertStatus(422);
        $response->assertSessionHasErrors(['answers']);
    }
}

