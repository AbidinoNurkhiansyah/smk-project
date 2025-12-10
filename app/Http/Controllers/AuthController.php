<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
            'new_password' => 'nullable|string|min:6|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
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

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Upload new profile picture
            $image = $request->file('profile_picture');
            $imageName = 'profile_' . session('user_id') . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('profile_pictures', $imageName, 'public');
            $updateData['profile_picture'] = $imagePath;
        }

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

    public function deleteProfilePicture(Request $request)
    {
        $user = DB::table('users')->where('user_id', session('user_id'))->first();

        if (!$user || !$user->profile_picture) {
            return back()->withErrors(['error' => 'Foto profil tidak ditemukan.']);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Update database
        DB::table('users')
            ->where('user_id', session('user_id'))
            ->update(['profile_picture' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus!');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email tidak terdaftar dalam sistem.'
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        // Generate token
        $token = Str::random(64);
        
        // Delete existing token for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        // Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // Generate reset link
        $resetLink = route('password.reset', ['token' => $token, 'email' => $request->email]);

        // TODO: Send email with reset link
        // For now, we'll show the link on the page (for development)
        // In production, you should send this via email
        
        return redirect()->route('password.reset', ['token' => $token, 'email' => $request->email])
            ->with('success', 'Link reset password telah dikirim. Silakan cek email Anda atau gunakan link di bawah ini.');
    }

    public function showResetPassword(Request $request)
    {
        $token = $request->get('token');
        $email = $request->get('email');
        
        if (!$token || !$email) {
            return redirect()->route('password.forgot')->with('error', 'Token atau email tidak valid.');
        }

        // Verify token
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetToken) {
            return redirect()->route('password.forgot')->with('error', 'Token reset password tidak valid atau telah kadaluarsa.');
        }

        // Check if token is expired (60 minutes)
        if (Carbon::parse($resetToken->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.forgot')->with('error', 'Token reset password telah kadaluarsa. Silakan request ulang.');
        }

        // Verify token hash
        if (!Hash::check($token, $resetToken->token)) {
            return redirect()->route('password.forgot')->with('error', 'Token reset password tidak valid.');
        }

        return view('auth.reset-password', compact('token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // Verify token
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetToken) {
            return back()->withErrors(['email' => 'Token reset password tidak valid atau telah kadaluarsa.'])->withInput();
        }

        // Check if token is expired (60 minutes)
        if (Carbon::parse($resetToken->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('password.forgot')->with('error', 'Token reset password telah kadaluarsa. Silakan request ulang.');
        }

        // Verify token hash
        if (!Hash::check($request->token, $resetToken->token)) {
            return back()->withErrors(['token' => 'Token reset password tidak valid.'])->withInput();
        }

        // Update password
        DB::table('users')
            ->where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password)
            ]);

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
