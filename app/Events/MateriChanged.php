<?php

namespace App\Events;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Room;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MateriChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $room;

    public ?int $kelas_id;

    public ?int $materi_id;

    public ?int $halaman;

    public string $judul;

    public string $tipe;

    public ?string $video_url;

    public ?string $konten;

    public string $pengirim;

    public function __construct(
        Room $room,
        ?Kelas $kelas,
        ?Materi $materi,
        ?int $halaman = null,
        string $pengirim = ''
    ) {
        $this->room = $room->slug;
        $this->kelas_id = $kelas?->id;
        $this->materi_id = $materi?->id;
        $this->halaman = $halaman;
        $this->judul = $materi?->judul ?? $kelas?->name ?? 'Materi';
        $this->tipe = $materi?->tipe ?? ($materi ? 'video' : 'teks');
        $this->video_url = $materi?->video_url;
        $this->konten = $materi?->konten;
        $this->pengirim = $pengirim;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('room.' . $this->room);
    }

    public function broadcastAs(): string
    {
        return 'materi.changed';
    }
}