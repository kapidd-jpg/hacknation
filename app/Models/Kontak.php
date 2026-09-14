<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'subjek',
        'kategori',
        'pesan',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }
}