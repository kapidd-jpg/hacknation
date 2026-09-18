<?php

namespace App\Support;

final class UserFoto
{
    public static function initials(?string $name): string
    {
        $name = preg_replace('/\s+/', ' ', trim((string) $name));

        if (blank($name)) {
            return 'U';
        }

        $parts = explode(' ', $name);
        $initials = strtoupper(mb_substr($parts[0], 0, 1));

        if (count($parts) > 1) {
            $initials .= strtoupper(mb_substr($parts[1], 0, 1));
        }

        return $initials;
    }
}