<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'slug',
        'name',
        'cat',
        'ico',
        'meta',
        'desc',
        'modul',
        'durasi',
        'price',
        'old',
        'bg',
        'color',
        'aktif',
    ];

    protected $casts = [
        'price' => 'integer',
        'old' => 'integer',
        'modul' => 'integer',
        'aktif' => 'boolean',
    ];

    public function materi()
    {
        return $this->hasMany(Materi::class);
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