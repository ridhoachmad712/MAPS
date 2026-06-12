<?php

namespace App\Support;

class PaletWarna
{
    /**
     * @return array{int, int, int}
     */
    public static function hexKeRgb(string $hex): array
    {
        $hex = ltrim(trim($hex), '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (! preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            $hex = '1e3a8a';
        }

        return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
    }

    /**
     * Bangkitkan gradasi 50–900 dari satu warna primer (di posisi 500):
     * tint dicampur putih, shade dicampur hitam.
     *
     * @return array<int|string, string> peta stop => hex
     */
    public static function gradasi(string $hex): array
    {
        [$r, $g, $b] = self::hexKeRgb($hex);

        $campur = function (float $rasio, int $target) use ($r, $g, $b): string {
            $c = fn (int $kanal): int => (int) round($kanal + ($target - $kanal) * $rasio);

            return sprintf('#%02x%02x%02x', $c($r), $c($g), $c($b));
        };

        return [
            50 => $campur(0.94, 255),
            100 => $campur(0.86, 255),
            200 => $campur(0.70, 255),
            300 => $campur(0.52, 255),
            400 => $campur(0.30, 255),
            500 => sprintf('#%02x%02x%02x', $r, $g, $b),
            600 => $campur(0.12, 0),
            700 => $campur(0.24, 0),
            800 => $campur(0.38, 0),
            900 => $campur(0.52, 0),
        ];
    }

    /**
     * Warna terang (true) atau gelap — untuk memilih warna teks yang kontras.
     */
    public static function terang(string $hex): bool
    {
        [$r, $g, $b] = self::hexKeRgb($hex);

        return (0.299 * $r + 0.587 * $g + 0.114 * $b) > 150;
    }
}
