<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [App\Http\Controllers\AuthController::class, 'showProfile'])->name('profile');
Route::post('/profile', [App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile.update');



Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome')->middleware('auth.session');

Route::get('/service', [App\Http\Controllers\ServiceController::class, 'index'])->name('service')->middleware('auth.session');

Route::get('/kuis', function () {
    return view('kuis');
})->name('kuis');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware('auth.session');

Route::get('/video-pembelajaran', [App\Http\Controllers\VideoController::class, 'index'])->name('video.index')->middleware('auth.session');
Route::get('/video/{id}', [App\Http\Controllers\VideoController::class, 'showVideo'])->name('video.show')->middleware('auth.session');
Route::post('/video/progress', [App\Http\Controllers\VideoController::class, 'updateProgress'])->name('video.progress')->middleware('auth.session');

// Game Routes
Route::prefix('game')->name('game.')->middleware('auth.session')->group(function () {
    Route::get('/', [App\Http\Controllers\GameController::class, 'index'])->name('index');
    Route::get('/play/{id}', [App\Http\Controllers\GameController::class, 'play'])->name('play');
    Route::post('/submit/{id}', [App\Http\Controllers\GameController::class, 'submit'])->name('submit');
    Route::get('/leaderboard', [App\Http\Controllers\GameController::class, 'leaderboard'])->name('leaderboard');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth.session')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/videos', [App\Http\Controllers\AdminController::class, 'videos'])->name('videos');
    Route::post('/videos/add', [App\Http\Controllers\AdminController::class, 'addVideo'])->name('add-video');
    Route::get('/videos/{id}/edit', [App\Http\Controllers\AdminController::class, 'editVideo'])->name('edit-video');
    Route::put('/videos/{id}/update', [App\Http\Controllers\AdminController::class, 'updateVideo'])->name('update-video');
    Route::delete('/videos/{id}/delete', [App\Http\Controllers\AdminController::class, 'deleteVideo'])->name('delete-video');
    
    
    Route::get('/students', [App\Http\Controllers\AdminController::class, 'students'])->name('students');
    Route::get('/students/{id}/progress', [App\Http\Controllers\AdminController::class, 'studentProgress'])->name('student-progress');
    Route::delete('/students/{id}/delete', [App\Http\Controllers\AdminController::class, 'deleteStudent'])->name('delete-student');
    
    Route::get('/analytics', [App\Http\Controllers\AdminController::class, 'analytics'])->name('analytics');
    Route::get('/leaderboard', [App\Http\Controllers\AdminController::class, 'leaderboard'])->name('leaderboard');
    
    // Quiz Management (Teacher Quiz)
    Route::get('/teacher-quiz', [App\Http\Controllers\TeacherQuizController::class, 'index'])->name('teacher-quiz');
    Route::get('/teacher-quiz/create', [App\Http\Controllers\TeacherQuizController::class, 'create'])->name('create-teacher-quiz');
    Route::post('/teacher-quiz', [App\Http\Controllers\TeacherQuizController::class, 'store'])->name('store-teacher-quiz');
    Route::get('/teacher-quiz/{id}', [App\Http\Controllers\TeacherQuizController::class, 'show'])->name('show-teacher-quiz');
    Route::get('/teacher-quiz/{id}/edit', [App\Http\Controllers\TeacherQuizController::class, 'edit'])->name('edit-teacher-quiz');
    Route::put('/teacher-quiz/{id}', [App\Http\Controllers\TeacherQuizController::class, 'update'])->name('update-teacher-quiz');
    Route::delete('/teacher-quiz/{id}', [App\Http\Controllers\TeacherQuizController::class, 'destroy'])->name('delete-teacher-quiz');
    
    // Quiz Analytics
    Route::get('/quiz-analytics', [App\Http\Controllers\QuizAnalyticsController::class, 'index'])->name('quiz-analytics');
    Route::get('/quiz-detail/{userId}/{quizId}', [App\Http\Controllers\QuizAnalyticsController::class, 'detail'])->name('quiz-detail');
});

// API Routes for AJAX
Route::get('/api/dashboard/progress', [App\Http\Controllers\DashboardController::class, 'getProgressData'])->name('api.dashboard.progress');
Route::post('/api/video/progress', [App\Http\Controllers\DashboardController::class, 'updateVideoProgress'])->name('api.video.progress');

