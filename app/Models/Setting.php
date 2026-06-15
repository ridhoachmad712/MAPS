<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'pengaturan';

    protected $fillable = ['kunci', 'nilai'];

    /**
     * Nilai bawaan — dipakai bila admin belum pernah mengubahnya,
     * sehingga instalasi baru langsung tampil benar tanpa seeding.
     *
     * @var array<string, string>
     */
    public const BAWAAN = [
        // Identitas
        'nama_aplikasi' => 'MAPS',
        'nama_pemilik' => 'Prodi Manajemen FEB UNM',
        'tagline' => 'Management Student Achievement Portfolio System',
        'logo' => '',
        'lebar_logo' => '', // lebar logo header (px); kosong = otomatis (tinggi 25px)

        // Warna (bawaan mengikuti palet default Tabler)
        'warna_primer' => '#066fd1',
        'warna_hero' => '#066fd1',
        'warna_navbar' => '#ffffff',
        'warna_footer' => '#f9fafb',

        // Hero beranda
        'hero_eyebrow' => 'Prodi Manajemen · FEB · Universitas Negeri Makassar',
        'hero_judul' => 'Data Capaian Mahasiswa',
        'hero_deskripsi' => 'Arsip prestasi, PKM, organisasi, MBKM, sertifikasi, dan publikasi mahasiswa yang telah diverifikasi program studi.',
        'hero_placeholder' => 'Cari judul capaian, penyelenggara, atau nama mahasiswa...',
        'hero_foto' => '',
        'hero_overlay' => '70',

        // Seksi beranda
        'sorotan_tampil' => '1',
        'sorotan_judul' => 'Sorotan Capaian',
        'sorotan_sub' => 'Capaian tingkat internasional dan nasional terbaru.',
        'galeri_tampil' => '1',
        'galeri_judul' => 'Mahasiswa Berprestasi',
        'galeri_sub' => 'Dengan capaian terverifikasi terbanyak yang tampil di halaman publik.',
        'grafik_tampil' => '1',
        'grafik_judul' => 'Sekilas Statistik',
        'grafik_sub' => 'Dihitung otomatis dari entri terverifikasi.',
        'cta_tampil' => '1',
        'cta_judul' => 'Butuh data lengkapnya?',
        'cta_deskripsi' => 'Jelajahi seluruh capaian terverifikasi dengan filter kategori, level, tahun, dan angkatan.',
        'cta_tombol' => 'Jelajahi Data Capaian',

        // Footer
        'footer_deskripsi' => 'Management Student Achievement Portfolio System — arsip dan showcase portofolio capaian mahasiswa Prodi Manajemen FEB UNM.',
        'footer_kontak1' => 'Program Studi Manajemen, Fakultas Ekonomi dan Bisnis',
        'footer_kontak2' => 'Universitas Negeri Makassar, Kampus Gunung Sari, Makassar',
        'footer_link_label' => '',
        'footer_link_url' => '',

        // Halaman Tentang
        'tentang_p1' => 'MAPS adalah sistem arsip dan etalase digital portofolio capaian mahasiswa Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar. Sistem ini mencatat dan mempublikasikan capaian mahasiswa dalam tujuh kategori: prestasi/kompetisi, Program Kreativitas Mahasiswa (PKM), organisasi, MBKM, sertifikasi, publikasi/karya ilmiah, dan dokumentasi kegiatan lainnya.',
        'tentang_p2' => 'Seluruh data yang tampil di halaman publik telah melalui proses verifikasi oleh program studi dan ditampilkan atas persetujuan mahasiswa yang bersangkutan. Statistik dihitung otomatis dari data — tidak ada angka yang diinput manual.',
        'tentang_kontak1' => 'Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar — Kampus Gunung Sari, Makassar',
        'tentang_kontak2' => 'Untuk koreksi data atau pertanyaan, hubungi admin program studi.',
    ];

    public static function get(string $kunci): string
    {
        $nilai = Cache::rememberForever(
            'pengaturan.'.$kunci,
            fn () => static::query()->where('kunci', $kunci)->value('nilai'),
        );

        return $nilai ?? (self::BAWAAN[$kunci] ?? '');
    }

    public static function set(string $kunci, ?string $nilai): void
    {
        static::query()->updateOrCreate(['kunci' => $kunci], ['nilai' => $nilai]);

        Cache::forget('pengaturan.'.$kunci);
    }

    /**
     * Seluruh nilai efektif (bawaan + yang sudah diubah) untuk mengisi form admin.
     *
     * @return array<string, string>
     */
    public static function semua(): array
    {
        $tersimpan = static::query()->pluck('nilai', 'kunci')->all();

        return collect(self::BAWAAN)
            ->map(fn (string $bawaan, string $kunci) => $tersimpan[$kunci] ?? $bawaan)
            ->all();
    }
}
