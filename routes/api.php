<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\VideoApiController;
use App\Http\Controllers\Api\GameApiController;
use App\Http\Controllers\Api\ProfileApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required)
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/forgot-password', [AuthApiController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthApiController::class, 'resetPassword']);

// Protected routes (require authentication)
Route::middleware(['api.auth'])->group(function () {
    // Auth routes
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);
    
    // Profile routes
    Route::get('/profile', [ProfileApiController::class, 'show']);
    Route::put('/profile', [ProfileApiController::class, 'update']);
    Route::post('/profile/picture', [ProfileApiController::class, 'updatePicture']);
    Route::delete('/profile/picture', [ProfileApiController::class, 'deletePicture']);
    
    // Dashboard routes
    Route::get('/dashboard', [DashboardApiController::class, 'index']);
    Route::get('/dashboard/progress', [DashboardApiController::class, 'getProgress']);
    
    // Video routes
    Route::get('/videos', [VideoApiController::class, 'index']);
    Route::get('/videos/{id}', [VideoApiController::class, 'show']);
    Route::post('/videos/{id}/progress', [VideoApiController::class, 'updateProgress']);
    
    // Game/Quiz routes
    Route::get('/quizzes', [GameApiController::class, 'index']);
    Route::get('/quizzes/{id}', [GameApiController::class, 'show']);
    Route::post('/quizzes/{id}/submit', [GameApiController::class, 'submit']);
    Route::get('/quizzes/{id}/time', [GameApiController::class, 'getQuizTime']);
    Route::get('/leaderboard', [GameApiController::class, 'leaderboard']);
});

