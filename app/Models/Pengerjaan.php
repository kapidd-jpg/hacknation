<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengerjaan extends Model
{
    use HasFactory;

    protected $table = 'pengerjaan';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'materi_id',
        'set_label',
        'tipe',
        'skor',
        'akurasi',
        'benar',
        'salah',
        'kosong',
        'total',
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'skor' => 'integer',
        'akurasi' => 'integer',
        'benar' => 'integer',
        'salah' => 'integer',
        'kosong' => 'integer',
        'total' => 'integer',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class);
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(Jawaban::class);
    }
}