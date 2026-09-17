<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'kategori',
        'nama',
        'slug',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function state()
    {
        return $this->hasOne(RoomState::class);
    }

    public function livekitName(): string
    {
        return 'pk-' . $this->slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}