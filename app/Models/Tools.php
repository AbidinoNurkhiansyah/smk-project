<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{
    use HasFactory;

    protected $table = 'tools';       // nama tabel
    protected $primaryKey = 'tool_id'; // primary key

    public $incrementing = false;     // karena bukan auto increment
    protected $keyType = 'int';       // tipe primary key

    public $timestamps = false;       // tabel tidak punya created_at/updated_at

    protected $fillable = [
        'tool_id',
        'tool_name',
        'tool_image',
    ];

    // Relasi ke Objects
    public function objects()
    {
        return $this->hasMany(Objects::class, 'tool_id', 'tool_id');
    }
}
