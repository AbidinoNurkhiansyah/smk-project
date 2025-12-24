<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure migrations are run
        // RefreshDatabase trait will handle this, but we need to ensure
        // the database connection is properly configured
        if (DB::getDefaultConnection() !== 'sqlite') {
            config(['database.default' => 'sqlite']);
            config(['database.connections.sqlite.database' => ':memory:']);
        }
    }

    /**
     * Create a test user
     */
    protected function createUser($attributes = [])
    {
        $defaults = [
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'class_id' => 1,
            'created_at' => now()
        ];

        $data = array_merge($defaults, $attributes);
        
        // Get next user_id
        $maxUserId = DB::table('users')->max('user_id') ?? 0;
        $data['user_id'] = $maxUserId + 1;

        DB::table('users')->insert($data);
        
        // Create points entry
        $maxPointId = DB::table('points')->max('point_id') ?? 0;
        DB::table('points')->insert([
            'point_id' => $maxPointId + 1,
            'user_id' => $data['user_id'],
            'total_point' => 0
        ]);

        return $data['user_id'];
    }

    /**
     * Create a test class
     */
    protected function createClass($attributes = [])
    {
        $defaults = [
            'class_id' => 1,
            'class_name' => 'Kelas X',
            'created_at' => now(),
            'updated_at' => now()
        ];

        $data = array_merge($defaults, $attributes);
        
        // Check if class already exists
        $exists = DB::table('classes')->where('class_id', $data['class_id'])->exists();
        if (!$exists) {
            DB::table('classes')->insert($data);
        }

        return $data['class_id'];
    }

    /**
     * Login as a user
     */
    protected function loginAsUser($userId = null)
    {
        if (!$userId) {
            $userId = $this->createUser();
        }

        $user = DB::table('users')->where('user_id', $userId)->first();
        
        if (!$user) {
            throw new \Exception("User with ID {$userId} not found");
        }
        
        session([
            'user_id' => $user->user_id,
            'user_name' => $user->user_name,
            'email' => $user->email,
            'role' => $user->role,
            'class_id' => $user->class_id
        ]);

        return $user;
    }

    /**
     * Login as admin
     */
    protected function loginAsAdmin()
    {
        $userId = $this->createUser([
            'role' => 'guru',
            'email' => 'admin@example.com'
        ]);

        return $this->loginAsUser($userId);
    }
}
