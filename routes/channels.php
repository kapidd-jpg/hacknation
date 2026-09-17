<?php

use App\Models\Room;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('room.{room}', function ($user, Room $room) {
    if (! $room->aktif) {
        return false;
    }

    if ($user->role === 'admin') {
        return true;
    }

    if ($user->role === 'guru') {
        return $user->kelasDiampu()->where('kelas.cat', $room->kategori)->exists();
    }

    return in_array($room->kategori, $user->aksesKategori(), true);
});
