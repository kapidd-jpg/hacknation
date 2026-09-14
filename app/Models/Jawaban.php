<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jawaban extends Model
{
    use HasFactory;

    protected $table = 'jawaban';

    protected $fillable = [
        'user_id',
        'pengerjaan_id',
        'soal_id',
        'pilihan',
        'benar',
    ];

    protected $casts = [
        'pilihan' => 'integer',
        'benar' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pengerjaan(): BelongsTo
    {
        return $this->belongsTo(Pengerjaan::class);
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class);
    }
}