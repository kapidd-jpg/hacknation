<?php

namespace App\Support;

use App\Models\User;
use Firebase\JWT\JWT;

class LiveKitService
{
    /**
     * Buat LiveKit access token (JWT HS256) untuk satu user pada satu room.
     *
     * Grant video di-build dari peran: semua peserta bisa publish/bicara,
     * sesuai keputusan "semua peserta bisa publish audio" di fitur voice room.
     *
     * @param  array<string, mixed>  $grants  override grant video (opsional)
     */
    public static function token(User $user, string $room, array $grants = []): string
    {
        $apiKey = (string) config('services.livekit.key');
        $apiSecret = (string) config('services.livekit.secret');

        $video = array_merge([
            'room' => $room,
            'roomJoin' => true,
            'canPublish' => true,
            'canSubscribe' => true,
            'canPublishData' => true,
        ], $grants);

        $now = time();

        $payload = [
            'iss' => $apiKey,
            'sub' => $apiKey,
            'nbf' => $now - 10,
            'iat' => $now,
            'exp' => $now + 6 * 3600,
            'jti' => base64_encode($room) . '.' . bin2hex(random_bytes(8)),
            'name' => $user->name,
            'metadata' => (string) $user->id,
            'video' => $video,
        ];

        return JWT::encode($payload, $apiSecret, 'HS256');
    }
}