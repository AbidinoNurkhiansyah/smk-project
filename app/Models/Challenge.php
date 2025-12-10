<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    // Nama tabel (opsional, karena Laravel otomatis pakai plural dari nama model)
    protected $table = 'challenges';

    // Primary key bukan 'id', tapi 'challenge_id'
    protected $primaryKey = 'challenge_id';

    // Kalau challenge_id bukan auto increment, tambahkan ini:
    public $incrementing = false;

    // Tipe primary key
    protected $keyType = 'int';

    // Karena tabel hanya punya created_at, tanpa updated_at
    public $timestamps = false;

    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'challenge_id',
        'object_id',
        'question',
        'correct_answer',
        'point',
        'created_at',
    ];

    // Relasi ke tabel objects
    public function object()
    {
        return $this->belongsTo(objects::class, 'object_id', 'object_id');
    }

    // Relasi ke Points
    public function points()
    {
        return $this->hasMany(Points::class, 'challenge_id', 'challenge_id');
    }
}
