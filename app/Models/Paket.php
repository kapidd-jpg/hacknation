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
        'kategori',
        'fitur',
        'aktif',
    ];

    protected $casts = [
        'kategori' => 'array',
        'fitur' => 'array',
        'aktif' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_paket')->withTimestamps();
    }
}