<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Objects extends Model
{
    //use HasFactory;

    // Nama tabel
    protected $table = 'objects';

    // Primary key
    protected $primaryKey = 'object_id';

    // Kalau object_id bukan auto increment
    public $incrementing = false;

    // Tipe primary key
    protected $keyType = 'int';

    // Tidak ada created_at / updated_at di tabel
    public $timestamps = false;

    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'object_id',
        'sparepart_id',
        'tool_id',
    ];

    // Relasi ke Challenge
    public function challenges()
    {
        return $this->hasMany(Challenge::class, 'object_id', 'object_id');
    }

    // Relasi ke Sparepart
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id', 'sparepart_id');
    }

    // Relasi ke Tool
    public function tool()
    {
        return $this->belongsTo(Tools::class, 'tool_id', 'tool_id');
    }
}
