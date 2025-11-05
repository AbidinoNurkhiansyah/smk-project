<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $credentials = $request->only('email', 'password');

        // Check if user exists
        $user = DB::table('users')->where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        // Check password
        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }

        // Store user in session
        session([
            'user_id' => $user->user_id,
            'user_name' => $user->user_name,
            'email' => $user->email,
            'role' => $user->role,
            'class_id' => $user->class_id
        ]);

        // Redirect based on role
        if ($user->role === 'kajur' || $user->role === 'guru') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('welcome');
        }
    }

    public function showRegister()
    {
        $classes = DB::table('classes')->get();
        return view('auth.register', compact('classes'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'class_id' => 'required|exists:classes,class_id'
        ]);

        // Get next user_id
        $nextUserId = DB::table('users')->max('user_id') + 1;
        
        // Create new user
        $userId = DB::table('users')->insertGetId([
            'user_id' => $nextUserId,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'class_id' => $request->class_id,
            'created_at' => now()
        ]);

        // Get next point_id
        $nextPointId = DB::table('points')->max('point_id') + 1;
        
        // Create points entry for new user
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

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }

    public function showProfile()
    {
        $user = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.user_id', session('user_id'))
            ->select('users.*', 'classes.class_name')
            ->first();

        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . session('user_id') . ',user_id',
            'current_password' => 'required',
            'new_password' => 'nullable|string|min:6|confirmed'
        ]);

        $user = DB::table('users')->where('user_id', session('user_id'))->first();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $updateData = [
            'user_name' => $request->user_name,
            'email' => $request->email
        ];

        // Update password if provided
        if ($request->new_password) {
            $updateData['password'] = Hash::make($request->new_password);
        }

        DB::table('users')
            ->where('user_id', session('user_id'))
            ->update($updateData);

        // Update session
        session([
            'user_name' => $request->user_name,
            'email' => $request->email
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
