<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'cat',
        'ico',
        'meta',
        'desc',
        'modul',
        'durasi',
        'siswa',
        'price',
        'old',
        'bg',
        'color',
        'aktif',
    ];

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }

    public function soal()
    {
        return $this->hasMany(Soal::class);
    }

    public function pengerjaan()
    {
        return $this->hasMany(Pengerjaan::class);
    }

    public function guruDiampu()
    {
        return $this->belongsToMany(User::class, 'pengampu')->withTimestamps();
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function siswaTerdaftar()
    {
        return $this->belongsToMany(User::class, 'pendaftaran')->withTimestamps();
    }
}