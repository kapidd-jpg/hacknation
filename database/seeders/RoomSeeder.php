<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = ['UTBK-SNBT', 'SMA', 'Bahasa'];

        foreach ($kategoris as $kategori) {
            for ($i = 1; $i <= 3; $i++) {
                Room::query()->updateOrCreate(
                    ['slug' => Str::slug($kategori) . '-' . $i],
                    [
                        'kategori' => $kategori,
                        'nama' => 'Ruang ' . $kategori . ' ' . $i,
                        'aktif' => true,
                    ]
                );
            }
        }
    }
}