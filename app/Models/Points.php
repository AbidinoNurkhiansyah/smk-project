<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Points extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'source',
        'description',
        'video_id',
        'challenge_id'
    ];

    protected $casts = [
        'points' => 'integer'
    ];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with video
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    // Relationship with challenge
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    // Scope for points by source
    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    // Scope for points by user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Get total points for a user
    public static function getTotalPointsForUser($userId)
    {
        return static::where('user_id', $userId)->sum('points');
    }

    // Add points for completing a video
    public static function addVideoCompletionPoints($userId, $videoId, $points = 10)
    {
        return static::create([
            'user_id' => $userId,
            'video_id' => $videoId,
            'points' => $points,
            'source' => 'video_completion',
            'description' => 'Menyelesaikan video pembelajaran'
        ]);
    }

    // Add points for quiz completion
    public static function addQuizPoints($userId, $challengeId, $points = 5)
    {
        return static::create([
            'user_id' => $userId,
            'challenge_id' => $challengeId,
            'points' => $points,
            'source' => 'quiz_completion',
            'description' => 'Menyelesaikan kuis'
        ]);
    }

    // Add points for daily login
    public static function addDailyLoginPoints($userId, $points = 2)
    {
        return static::create([
            'user_id' => $userId,
            'points' => $points,
            'source' => 'daily_login',
            'description' => 'Login harian'
        ]);
    }
}