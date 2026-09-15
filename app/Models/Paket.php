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

    /**
     * Peta harga paket per kategori: ['UTBK-SNBT' => ['harga' => 599000, 'harga_lama' => 799000, 'key' => 'utbk']]
     */
    public static function pricesByKategori(): array
    {
        $map = [];

        foreach (self::query()->where('aktif', true)->orderBy('harga')->get() as $paket) {
            foreach (($paket->kategori ?? []) as $cat) {
                if (! isset($map[$cat])) {
                    $map[$cat] = [
                        'harga' => (int) $paket->harga,
                        'harga_lama' => (int) $paket->harga_lama,
                        'key' => $paket->key,
                    ];
                }
            }
        }

        return $map;
    }

    /**
     * Format harga ke string 'Rp 599K' (dibulatkan ke ribuan terdekat).
     */
    public static function formatHarga(?int $nilai): string
    {
        $nilai = (int) $nilai;

        if ($nilai <= 0) {
            return '';
        }

        if ($nilai % 1000 === 0) {
            return 'Rp ' . number_format($nilai / 1000, 0, ',', '.') . 'K';
        }

        return 'Rp ' . number_format($nilai, 0, ',', '.');
    }
}