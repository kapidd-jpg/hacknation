<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomState extends Model
{
    use HasFactory;

    protected $table = 'room_state';

    protected $fillable = [
        'room_id',
        'kelas_id',
        'materi_id',
        'halaman',
        'pengirim',
        'play',
        'waktu',
    ];

    protected $casts = [
        'play' => 'boolean',
        'waktu' => 'float',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
}