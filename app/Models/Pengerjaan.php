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
        'set_label',
        'tipe',
        'skor',
        'akurasi',
        'benar',
        'total',
    ];

    protected $casts = [
        'skor' => 'integer',
        'akurasi' => 'integer',
        'benar' => 'integer',
        'total' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(Jawaban::class);
    }
}