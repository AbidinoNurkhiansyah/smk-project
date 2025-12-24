<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthTest extends TestCase
{
    /**
     * Test user can view login page
     */
    public function test_user_can_view_login_page()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test user can login with valid credentials
     */
    public function test_user_can_login_with_valid_credentials()
    {
        // Create test class
        $this->createClass();
        
        // Create test user
        $userId = $this->createUser([
            'email' => 'siswa@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'siswa@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/welcome');
        $this->assertNotNull(session('user_id'));
    }

    /**
     * Test user cannot login with invalid password
     */
    public function test_user_cannot_login_with_invalid_password()
    {
        $this->createClass();
        $this->createUser([
            'email' => 'siswa@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'siswa@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertNull(session('user_id'));
    }

    /**
     * Test user cannot login with non-existent email
     */
    public function test_user_cannot_login_with_nonexistent_email()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test login validation - empty fields
     */
    public function test_login_requires_email_and_password()
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    /**
     * Test login validation - invalid email format
     */
    public function test_login_requires_valid_email_format()
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test user can view register page
     */
    public function test_user_can_view_register_page()
    {
        $this->createClass();
        
        $response = $this->get('/register');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * Test user can register with valid data
     */
    public function test_user_can_register_with_valid_data()
    {
        $this->createClass();

        $response = $this->post('/register', [
            'user_name' => 'Siswa Baru',
            'email' => 'siswabaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'class_id' => 1
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'email' => 'siswabaru@example.com',
            'user_name' => 'Siswa Baru',
            'role' => 'siswa'
        ]);
    }

    /**
     * Test registration fails with duplicate email
     */
    public function test_registration_fails_with_duplicate_email()
    {
        $this->createClass();
        $this->createUser(['email' => 'existing@example.com']);

        $response = $this->post('/register', [
            'user_name' => 'Siswa Baru',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'class_id' => 1
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test registration validation - password mismatch
     */
    public function test_registration_requires_password_confirmation()
    {
        $this->createClass();

        $response = $this->post('/register', [
            'user_name' => 'Siswa Baru',
            'email' => 'siswabaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456',
            'class_id' => 1
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test registration validation - password minimum length
     */
    public function test_registration_requires_password_minimum_length()
    {
        $this->createClass();

        $response = $this->post('/register', [
            'user_name' => 'Siswa Baru',
            'email' => 'siswabaru@example.com',
            'password' => 'pass123',
            'password_confirmation' => 'pass123',
            'class_id' => 1
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test user can logout
     */
    public function test_user_can_logout()
    {
        $this->createClass();
        $this->loginAsUser();

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
        $this->assertNull(session('user_id'));
    }

    /**
     * Test user can view profile page
     */
    public function test_user_can_view_profile_page()
    {
        $this->createClass();
        $this->loginAsUser();

        $response = $this->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('auth.profile');
    }

    /**
     * Test user can update profile
     */
    public function test_user_can_update_profile()
    {
        $this->createClass();
        $userId = $this->createUser();
        $this->loginAsUser($userId);

        $response = $this->post('/profile', [
            'user_name' => 'Nama Baru',
            'email' => 'emailbaru@example.com'
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'user_id' => $userId,
            'user_name' => 'Nama Baru',
            'email' => 'emailbaru@example.com'
        ]);
    }

    /**
     * Test user can change password
     */
    public function test_user_can_change_password()
    {
        $this->createClass();
        $userId = $this->createUser([
            'password' => Hash::make('oldpassword123')
        ]);
        $this->loginAsUser($userId);

        $response = $this->post('/change-password', [
            'current_password' => 'oldpassword123',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');
        
        $user = DB::table('users')->where('user_id', $userId)->first();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /**
     * Test change password fails with wrong current password
     */
    public function test_change_password_fails_with_wrong_current_password()
    {
        $this->createClass();
        $userId = $this->createUser([
            'password' => Hash::make('oldpassword123')
        ]);
        $this->loginAsUser($userId);

        $response = $this->post('/change-password', [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123'
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    /**
     * Test user can request password reset
     */
    public function test_user_can_request_password_reset()
    {
        $this->createClass();
        $this->createUser(['email' => 'test@example.com']);

        $response = $this->post('/forgot-password', [
            'email' => 'test@example.com'
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'test@example.com'
        ]);
    }

    /**
     * Test password reset fails with non-existent email
     */
    public function test_password_reset_fails_with_nonexistent_email()
    {
        $response = $this->post('/forgot-password', [
            'email' => 'nonexistent@example.com'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test user can reset password with valid token
     */
    public function test_user_can_reset_password_with_valid_token()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword123')
        ]);

        // Create reset token
        $token = \Illuminate\Support\Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
        
        $user = DB::table('users')->where('user_id', $userId)->first();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /**
     * Test password reset fails with invalid token
     */
    public function test_password_reset_fails_with_invalid_token()
    {
        $this->createClass();
        $this->createUser(['email' => 'test@example.com']);

        $response = $this->post('/reset-password', [
            'token' => 'invalid-token',
            'email' => 'test@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertSessionHasErrors();
    }

    /**
     * Test admin redirects to admin dashboard after login
     */
    public function test_admin_redirects_to_admin_dashboard_after_login()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'admin@example.com',
            'role' => 'guru',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/admin');
    }

    /**
     * Test student redirects to welcome page after login
     */
    public function test_student_redirects_to_welcome_after_login()
    {
        $this->createClass();
        $userId = $this->createUser([
            'email' => 'siswa@example.com',
            'role' => 'siswa',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'siswa@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/welcome');
    }

    /**
     * Test protected routes require authentication
     */
    public function test_protected_routes_require_authentication()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/video-pembelajaran');
        $response->assertRedirect('/login');

        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }
}

