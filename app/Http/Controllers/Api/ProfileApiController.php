<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileApiController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        
        $userData = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.user_id', $user->user_id)
            ->select('users.*', 'classes.class_name')
            ->first();

        $points = DB::table('points')
            ->where('user_id', $user->user_id)
            ->value('total_point') ?? 0;

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $userData->user_id,
                'user_name' => $userData->user_name,
                'email' => $userData->email,
                'role' => $userData->role,
                'class_id' => $userData->class_id,
                'class_name' => $userData->class_name,
                'profile_picture' => $userData->profile_picture ? url('storage/' . $userData->profile_picture) : null,
                'total_points' => $points,
                'created_at' => $userData->created_at,
            ]
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'current_password' => 'required',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $userData = DB::table('users')->where('user_id', $user->user_id)->first();

        // Check current password
        if (!Hash::check($request->current_password, $userData->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini salah.'
            ], 400);
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
            ->where('user_id', $user->user_id)
            ->update($updateData);

        $updatedUser = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.user_id', $user->user_id)
            ->select('users.*', 'classes.class_name')
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
            'data' => [
                'user_id' => $updatedUser->user_id,
                'user_name' => $updatedUser->user_name,
                'email' => $updatedUser->email,
                'role' => $updatedUser->role,
                'class_id' => $updatedUser->class_id,
                'class_name' => $updatedUser->class_name,
                'profile_picture' => $updatedUser->profile_picture ? url('storage/' . $updatedUser->profile_picture) : null,
            ]
        ]);
    }

    public function updatePicture(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $userData = DB::table('users')->where('user_id', $user->user_id)->first();

        // Delete old profile picture if exists
        if ($userData->profile_picture && Storage::disk('public')->exists($userData->profile_picture)) {
            Storage::disk('public')->delete($userData->profile_picture);
        }

        // Upload new profile picture
        $image = $request->file('profile_picture');
        $imageName = 'profile_' . $user->user_id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs('profile_pictures', $imageName, 'public');

        DB::table('users')
            ->where('user_id', $user->user_id)
            ->update(['profile_picture' => $imagePath]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui!',
            'data' => [
                'profile_picture' => url('storage/' . $imagePath)
            ]
        ]);
    }

    public function deletePicture(Request $request)
    {
        $user = $request->user();
        
        $userData = DB::table('users')->where('user_id', $user->user_id)->first();

        if (!$userData || !$userData->profile_picture) {
            return response()->json([
                'success' => false,
                'message' => 'Foto profil tidak ditemukan.'
            ], 404);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($userData->profile_picture)) {
            Storage::disk('public')->delete($userData->profile_picture);
        }

        // Update database
        DB::table('users')
            ->where('user_id', $user->user_id)
            ->update(['profile_picture' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus!'
        ]);
    }
}

