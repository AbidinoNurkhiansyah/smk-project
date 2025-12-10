<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoProgress extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'video_id',
        'completion_percentage',
        'time_watched',
        'last_watched_at',
        'is_completed'
    ];

    protected $casts = [
        'last_watched_at' => 'datetime',
        'is_completed' => 'boolean',
        'completion_percentage' => 'integer',
        'time_watched' => 'integer'
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

    // Scope for completed videos
    public function scopeCompleted($query)
    {
        return $query->where('completion_percentage', 100);
    }

    // Scope for in progress videos
    public function scopeInProgress($query)
    {
        return $query->where('completion_percentage', '>', 0)
                    ->where('completion_percentage', '<', 100);
    }

    // Scope for not started videos
    public function scopeNotStarted($query)
    {
        return $query->where('completion_percentage', 0);
    }

    // Update progress and mark as completed if 100%
    public function updateProgress($percentage, $timeWatched = null)
    {
        $this->completion_percentage = $percentage;
        $this->time_watched = $timeWatched ?? $this->time_watched;
        $this->last_watched_at = now();
        
        if ($percentage >= 100) {
            $this->is_completed = true;
        }
        
        $this->save();
        
        return $this;
    }

    // Get progress status
    public function getStatusAttribute()
    {
        if ($this->completion_percentage == 0) {
            return 'not_started';
        } elseif ($this->completion_percentage == 100) {
            return 'completed';
        } else {
            return 'in_progress';
        }
    }
}