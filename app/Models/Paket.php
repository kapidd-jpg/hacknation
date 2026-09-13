<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paket';

    protected $fillable = [
        'key',
        'nama',
        'tag',
        'harga',
        'harga_lama',
        'kuota',
        'fitur',
        'aktif',
    ];

    protected $casts = [
        'fitur' => 'array',
        'aktif' => 'boolean',
    ];
}