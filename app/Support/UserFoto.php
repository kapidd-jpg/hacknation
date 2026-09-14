<?php

namespace App\Support;

final class UserFoto
{
    public const DEFAULT_URL = 'https://www.figma.com/api/mcp/asset/4b9001eb-320b-418b-b43a-9ddbb0503794.png';

    public const MAX_SIZE = 512;

    public static function src(?string $foto): string
    {
        return blank($foto) ? self::DEFAULT_URL : route('user.foto');
    }

    public static function compress(?string $foto): ?string
    {
        if (blank($foto) || str_starts_with($foto, 'http://') || str_starts_with($foto, 'https://')) {
            return $foto;
        }

        if (! preg_match('/^data:image\/(png|jpeg|webp|gif);base64,(.+)$/s', $foto, $m)) {
            return $foto;
        }

        if (! function_exists('imagecreatefromstring')) {
            return $foto;
        }

        try {
            $bytes = base64_decode($m[2], true);
            if ($bytes === false || $bytes === '') {
                return $foto;
            }

            $img = @imagecreatefromstring($bytes);
            if ($img === false) {
                return $foto;
            }

            $w = imagesx($img);
            $h = imagesy($img);

            if (strlen($bytes) <= (150 * 1024) && $w <= (self::MAX_SIZE * 2) && $h <= (self::MAX_SIZE * 2)) {
                imagedestroy($img);

                return $foto;
            }

            if ($w > self::MAX_SIZE || $h > self::MAX_SIZE) {
                $scale = min(self::MAX_SIZE / $w, self::MAX_SIZE / $h);
                $nw = max(1, (int) round($w * $scale));
                $nh = max(1, (int) round($h * $scale));
                $resized = imagecreatetruecolor($nw, $nh);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
                imagedestroy($img);
                $img = $resized;
            }

            $binary = null;
            $prefix = 'data:image/jpeg;base64,';

            if (function_exists('imagewebp')) {
                ob_start();
                if (imagewebp($img, null, 82)) {
                    $binary = ob_get_clean();
                    $prefix = 'data:image/webp;base64,';
                } else {
                    ob_end_clean();
                }
            }

            if ($binary === null) {
                ob_start();
                $binary = imagejpeg($img, null, 85) ? ob_get_clean() : null;
                if ($binary === null) {
                    ob_end_clean();
                }
            }

            imagedestroy($img);

            if ($binary === null || $binary === '') {
                return $foto;
            }

            return $prefix . base64_encode($binary);
        } catch (\Throwable $e) {
            return $foto;
        }
    }
}