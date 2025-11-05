<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_points',
        'position',
        'level',
        'badges_earned',
        'last_updated'
    ];

    protected $casts = [
        'total_points' => 'integer',
        'position' => 'integer',
        'level' => 'integer',
        'badges_earned' => 'array',
        'last_updated' => 'datetime'
    ];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for top performers
    public function scopeTopPerformers($query, $limit = 10)
    {
        return $query->orderBy('total_points', 'desc')
                    ->orderBy('last_updated', 'desc')
                    ->limit($limit);
    }

    // Update leaderboard position
    public function updatePosition()
    {
        $this->position = static::where('total_points', '>', $this->total_points)->count() + 1;
        $this->last_updated = now();
        $this->save();
    }

    // Calculate user level based on points
    public function calculateLevel()
    {
        $points = $this->total_points;
        
        if ($points >= 1000) {
            return 5; // Expert
        } elseif ($points >= 500) {
            return 4; // Advanced
        } elseif ($points >= 200) {
            return 3; // Intermediate
        } elseif ($points >= 50) {
            return 2; // Beginner
        } else {
            return 1; // Newbie
        }
    }

    // Update leaderboard for a user
    public static function updateUserLeaderboard($userId)
    {
        $totalPoints = Points::getTotalPointsForUser($userId);
        
        $leaderboard = static::updateOrCreate(
            ['user_id' => $userId],
            [
                'total_points' => $totalPoints,
                'level' => static::calculateLevelFromPoints($totalPoints),
                'last_updated' => now()
            ]
        );

        // Update position
        $leaderboard->updatePosition();
        
        return $leaderboard;
    }

    // Calculate level from points
    public static function calculateLevelFromPoints($points)
    {
        if ($points >= 1000) {
            return 5; // Expert
        } elseif ($points >= 500) {
            return 4; // Advanced
        } elseif ($points >= 200) {
            return 3; // Intermediate
        } elseif ($points >= 50) {
            return 2; // Beginner
        } else {
            return 1; // Newbie
        }
    }

    // Get level name
    public function getLevelNameAttribute()
    {
        $levels = [
            1 => 'Newbie',
            2 => 'Beginner',
            3 => 'Intermediate',
            4 => 'Advanced',
            5 => 'Expert'
        ];

        return $levels[$this->level] ?? 'Unknown';
    }

    // Get top 10 leaderboard
    public static function getTopTen()
    {
        return static::with('user')
                    ->topPerformers(10)
                    ->get();
    }
}