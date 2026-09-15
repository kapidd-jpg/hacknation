<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    protected $table = 'kontak';

    protected $fillable = [
        'nama',
        'email',
        'subjek',
        'kategori',
        'pesan',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];
}