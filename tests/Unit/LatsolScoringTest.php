<?php

namespace Tests\Unit;

use App\Models\Soal;
use App\Services\LatsolService;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class LatsolScoringTest extends TestCase
{
    private function soals(array $defs): Collection
    {
        $models = [];
        foreach ($defs as [$id, $kunci]) {
            $model = new Soal(['kunci' => $kunci]);
            $model->id = $id;
            $models[] = $model;
        }

        return new Collection($models);
    }

    public function test_hitung_skor_rounding_dan_breakdown(): void
    {
        $service = new LatsolService();
        $soals = $this->soals([[1, 0], [2, 1], [3, 2]]);

        $hasil = $service->hitungBreakdown($soals, [1 => 0, 2 => 1, 3 => 0]);

        $this->assertSame(2, $hasil['benar']);   // soal 1 & 2
        $this->assertSame(1, $hasil['salah']);   // soal 3 (pilihan 0 vs kunci 2)
        $this->assertSame(0, $hasil['kosong']);
        $this->assertSame(3, $hasil['total']);
        $this->assertSame(67, $hasil['skor']);   // round(2/3*100) = 67
        $this->assertSame(67, $hasil['akurasi']); // round(2/3*100) tanpa kosong
    }

    public function test_skor_tanpa_minus_untuk_jawaban_salah(): void
    {
        $service = new LatsolService();
        $soals = $this->soals([[1, 0], [2, 1]]);

        $hasil = $service->hitungBreakdown($soals, [1 => 3, 2 => 0]);

        $this->assertSame(0, $hasil['benar']);
        $this->assertSame(2, $hasil['salah']);
        $this->assertSame(0, $hasil['skor']); // tidak ada minus
        $this->assertSame(0, $hasil['akurasi']);
    }

    public function test_akurasi_mengabaikan_kosong(): void
    {
        $service = new LatsolService();
        $soals = $this->soals([[1, 0], [2, 1], [3, 2], [4, 3]]);

        // 2 benar, 1 salah, 1 kosong
        $hasil = $service->hitungBreakdown($soals, [1 => 0, 2 => 1, 3 => 3]);

        $this->assertSame(2, $hasil['benar']);
        $this->assertSame(1, $hasil['salah']);
        $this->assertSame(1, $hasil['kosong']);
        $this->assertSame(4, $hasil['total']);
        $this->assertSame(50, $hasil['skor']);   // round(2/4*100)
        $this->assertSame(67, $hasil['akurasi']); // round(2/3*100) - kosong dikecualikan
    }

    public function test_status_tiap_jawaban_benar_salah_kosong(): void
    {
        $service = new LatsolService();
        $soals = $this->soals([[1, 0], [2, 1], [3, 2]]);

        $hasil = $service->hitungBreakdown($soals, [2 => 1, 3 => 0]);

        $olehSoal = function (array $rows, int $soalId) {
            return collect($rows)->firstWhere('soal_id', $soalId);
        };

        $this->assertSame('kosong', $olehSoal($hasil['statusRows'], 1)['status']);
        $this->assertFalse($olehSoal($hasil['statusRows'], 1)['benar']);
        $this->assertNull($olehSoal($hasil['statusRows'], 1)['pilihan']);

        $this->assertSame('benar', $olehSoal($hasil['statusRows'], 2)['status']);
        $this->assertTrue($olehSoal($hasil['statusRows'], 2)['benar']);

        $this->assertSame('salah', $olehSoal($hasil['statusRows'], 3)['status']);
        $this->assertFalse($olehSoal($hasil['statusRows'], 3)['benar']);
    }
}