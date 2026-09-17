<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomPresence extends Model
{
    use HasFactory;

    protected $table = 'room_presence';

    protected $fillable = [
        'room_id',
        'user_id',
        'nama',
        'role',
        'voice',
        'last_seen_at',
    ];

    protected $casts = [
        'voice' => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}