<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soal';

    protected $fillable = [
        'kelas_id',
        'materi_id',
        'set_label',
        'pertanyaan',
        'opsi',
        'kunci',
        'pembahasan',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'opsi' => 'array',
        'kunci' => 'integer',
        'urutan' => 'integer',
        'aktif' => 'boolean',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class);
    }
}