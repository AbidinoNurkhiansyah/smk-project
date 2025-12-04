<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $table = 'spareparts';       // nama tabel
    protected $primaryKey = 'sparepart_id'; // primary key

    public $incrementing = false;          // karena bukan auto increment
    protected $keyType = 'int';            // tipe primary key

    public $timestamps = false;            // tabel tidak punya created_at/updated_at

    protected $fillable = [
        'sparepart_id',
        'sparepart_name',
        'sparepart_image',
    ];

    // Relasi ke Objects
    public function objects()
    {
        return $this->hasMany(Objects::class, 'sparepart_id', 'sparepart_id');
    }
}
