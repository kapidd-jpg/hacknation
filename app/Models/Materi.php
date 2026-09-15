<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'kelas_id',
        'judul',
        'tutor',
        'pertemuan',
        'durasi',
        'bab',
        'urutan',
        'tipe',
        'video_url',
        'konten',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}