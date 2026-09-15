<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateGuruAccount extends Command
{
    protected $signature = 'akun:guru
        {--nama= : Nama lengkap guru}
        {--email= : Email guru}
        {--password= : Password (opsional, jika kosong akan digenerate otomatis)}
        {--admin : Jadikan akun dengan role admin (operator: kelola paket & akun siswa)}';

    protected $description = 'Buat akun guru/admin baru (oleh operator). Daftar publik hanya untuk siswa.';

    public function handle(): int
    {
        $nama = $this->option('nama') ?: $this->ask('Nama lengkap guru');
        $email = $this->option('email') ?: $this->ask('Email guru');

        if (blank($nama) || blank($email)) {
            $this->components->error('Nama dan email wajib diisi.');

            return self::FAILURE;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->components->error("Format email tidak valid: {$email}");

            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->components->error("Email sudah terdaftar: {$email}");

            return self::FAILURE;
        }

        $password = $this->option('password') ?: Str::password(12);

        $role = $this->option('admin') ? 'admin' : 'guru';

$user = new User();
        $user->name = $nama;
        $user->email = $email;
        $user->password = $password;
        $user->role = $role;
        $user->save();

        $this->components->info('Akun ' . $role . ' berhasil dibuat.');
        $this->newLine();
        $this->line("  Nama     : {$user->name}");
        $this->line("  Email    : {$user->email}");
        $this->line("  Role     : {$role}");
        $this->line("  Password : {$password}");
        $this->newLine();
        $this->components->warn('Simpan & amankan password ini. Tidak akan ditampilkan lagi.');

        return self::SUCCESS;
    }
}