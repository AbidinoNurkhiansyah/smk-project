<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'duration',
        'thumbnail',
        'video_url',
        'difficulty_level',
        'order_index',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration' => 'integer'
    ];

    // Relationship with video progress
    public function progress()
    {
        return $this->hasOne(VideoProgress::class);
    }

    // Relationship with user video stats
    public function userStats()
    {
        return $this->hasMany(UserVideoStats::class);
    }

    // Scope for active videos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope for difficulty level
    public function scopeByDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    // Get formatted duration
    public function getFormattedDurationAttribute()
    {
        $minutes = $this->duration;
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d', $hours, $mins);
        }
        
        return sprintf('%d:%02d', 0, $mins);
    }

    // Get progress percentage for a specific user
    public function getProgressForUser($userId)
    {
        $progress = $this->progress()->where('user_id', $userId)->first();
        return $progress ? $progress->completion_percentage : 0;
    }
}