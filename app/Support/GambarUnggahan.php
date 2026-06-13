<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class GambarUnggahan
{
    /**
     * Simpan gambar unggahan ke disk public dengan ukuran terkendali:
     * sisi terpanjang dibatasi $maksSisi piksel, dikodekan ulang sebagai
     * WebP (kualitas 82) agar ringan diunduh pengunjung. SVG disimpan
     * apa adanya. Mengembalikan path relatif pada disk public.
     */
    public static function simpan(UploadedFile $berkas, string $direktori, int $maksSisi): string
    {
        if (strtolower($berkas->getClientOriginalExtension()) === 'svg') {
            return $berkas->store($direktori, 'public');
        }

        $gambar = ImageManager::usingDriver(new Driver)
            ->decodePath($berkas->getRealPath())
            ->scaleDown($maksSisi, $maksSisi)
            ->encode(new WebpEncoder(quality: 82));

        $path = $direktori.'/'.Str::random(40).'.webp';
        Storage::disk('public')->put($path, (string) $gambar);

        return $path;
    }
}
