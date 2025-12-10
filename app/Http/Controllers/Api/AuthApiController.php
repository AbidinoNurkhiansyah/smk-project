<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan.'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah.'
            ], 401);
        }

        // Generate API token
        $token = Str::random(60);
        
        // Update user with API token
        DB::table('users')
            ->where('user_id', $user->user_id)
            ->update(['api_token' => $token]);

        // Get class name
        $class = DB::table('classes')
            ->where('class_id', $user->class_id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'user' => [
                    'user_id' => $user->user_id,
                    'user_name' => $user->user_name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'class_id' => $user->class_id,
                    'class_name' => $class->class_name ?? null,
                    'profile_picture' => $user->profile_picture ? url('storage/' . $user->profile_picture) : null,
                ]
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'class_id' => 'required|exists:classes,class_id'
        ]);

        $nextUserId = DB::table('users')->max('user_id') + 1;
        $apiToken = Str::random(60);
        
        $userId = DB::table('users')->insertGetId([
            'user_id' => $nextUserId,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'class_id' => $request->class_id,
            'api_token' => $apiToken,
            'created_at' => now()
        ]);

        // Create points entry
        $nextPointId = DB::table('points')->max('point_id') + 1;
        DB::table('points')->insert([
            'point_id' => $nextPointId,
            'user_id' => $nextUserId,
            'total_point' => 0
        ]);

        // Create video progress for all videos
        $videos = DB::table('videos')->get();
        $nextProgressId = DB::table('video_progress')->max('progress_id') + 1;
        
        foreach ($videos as $video) {
            DB::table('video_progress')->insert([
                'progress_id' => $nextProgressId,
                'user_id' => $nextUserId,
                'video_id' => $video->video_id,
                'progress' => 0,
                'is_completed' => false
            ]);
            $nextProgressId++;
        }

        $class = DB::table('classes')
            ->where('class_id', $request->class_id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil!',
            'data' => [
                'token' => $apiToken,
                'user' => [
                    'user_id' => $nextUserId,
                    'user_name' => $request->user_name,
                    'email' => $request->email,
                    'role' => 'siswa',
                    'class_id' => $request->class_id,
                    'class_name' => $class->class_name ?? null,
                ]
            ]
        ], 201);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        
        if ($user) {
            DB::table('users')
                ->where('user_id', $user->user_id)
                ->update(['api_token' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.'
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        
        $class = DB::table('classes')
            ->where('class_id', $user->class_id)
            ->first();

        $points = DB::table('points')
            ->where('user_id', $user->user_id)
            ->value('total_point') ?? 0;

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'role' => $user->role,
                'class_id' => $user->class_id,
                'class_name' => $class->class_name ?? null,
                'profile_picture' => $user->profile_picture ? url('storage/' . $user->profile_picture) : null,
                'total_points' => $points,
            ]
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan.'
            ], 404);
        }

        $token = Str::random(64);
        
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // TODO: Send email with reset link
        // For now, return token (in production, send via email)
        
        return response()->json([
            'success' => true,
            'message' => 'Link reset password telah dikirim ke email Anda.',
            'data' => [
                'token' => $token, // Remove this in production
                'email' => $request->email
            ]
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token reset password tidak valid atau telah kadaluarsa.'
            ], 400);
        }

        if (!Hash::check($request->token, $resetToken->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token reset password tidak valid.'
            ], 400);
        }

        // Check if token is expired (60 minutes)
        $expired = \Carbon\Carbon::parse($resetToken->created_at)->addMinutes(60)->isPast();
        if ($expired) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json([
                'success' => false,
                'message' => 'Token reset password telah kadaluarsa. Silakan request ulang.'
            ], 400);
        }

        // Update password
        DB::table('users')
            ->where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset!'
        ]);
    }
}

