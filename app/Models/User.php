<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'sekolah',
        'kelas_jurusan',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['guru', 'admin'], true);
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function pakets()
    {
        return $this->belongsToMany(Paket::class, 'user_paket')->withTimestamps();
    }

    public function paketKeys(): array
    {
        return $this->pakets->pluck('key')->all();
    }

    public function hasPaket(): bool
    {
        return $this->pakets->isNotEmpty();
    }

    public function hasAnyPaket(): bool
    {
        return $this->hasPaket();
    }

    public function aksesKategori(): array
    {
        $kategori = [];
        foreach ($this->pakets as $paket) {
            foreach (($paket->kategori ?? []) as $cat) {
                $kategori[$cat] = true;
            }
        }

        return array_keys($kategori);
    }

    public function kelasTerdaftar()
    {
        return $this->belongsToMany(Kelas::class, 'pendaftaran')->withTimestamps();
    }

    public function kelasDiampu()
    {
        return $this->belongsToMany(Kelas::class, 'pengampu')->withTimestamps();
    }

    public function pengerjaan()
    {
        return $this->hasMany(Pengerjaan::class);
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class);
    }

    public function nilaiHasil()
    {
        return $this->hasMany(Nilai::class);
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function unreadUserNotifications()
    {
        return $this->userNotifications()->unread();
    }

    public function unreadNotificationsCount(): int
    {
        return $this->unreadUserNotifications()->count();
    }

    public function progresModul()
    {
        return $this->hasMany(ProgresModul::class);
    }

    public function completedModulIds(): \Illuminate\Support\Collection
    {
        return $this->progresModul()->pluck('materi_id');
    }

    public function isModulDone(Materi $materi): bool
    {
        return $this->progresModul()->where('materi_id', $materi->id)->exists();
    }

    public function progresPct(Kelas $kelas): int
    {
        $total = $kelas->materi()->count();
        if ($total === 0) {
            return 0;
        }

        $completed = $this->progresModul()
            ->whereHas('materi', fn ($q) => $q->where('kelas_id', $kelas->id))
            ->count();

        return (int) round($completed / $total * 100);
    }
}