<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PengaturanAdminController extends Controller
{
    /** Kunci pengaturan bertipe sakelar (disimpan '1'/'0'). */
    private const SAKELAR = ['sorotan_tampil', 'galeri_tampil', 'grafik_tampil', 'cta_tampil'];

    /** Kunci pengaturan bertipe berkas gambar. */
    private const BERKAS = ['logo', 'hero_foto'];

    public function edit()
    {
        return view('admin.pengaturan.edit', ['nilai' => Setting::semua()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            // Identitas
            'nama_aplikasi' => ['required', 'string', 'max:50'],
            'nama_pemilik' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:150'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'hapus_logo' => ['nullable', 'boolean'],

            // Warna
            'warna_primer' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'warna_hero' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'warna_navbar' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'warna_footer' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],

            // Hero beranda
            'hero_eyebrow' => ['nullable', 'string', 'max:150'],
            'hero_judul' => ['required', 'string', 'max:100'],
            'hero_deskripsi' => ['nullable', 'string', 'max:300'],
            'hero_placeholder' => ['nullable', 'string', 'max:150'],
            'hero_overlay' => ['nullable', 'integer', 'min:0', 'max:95'],
            'hero_foto' => ['nullable', 'image', 'max:4096'],
            'hapus_hero_foto' => ['nullable', 'boolean'],

            // Seksi beranda
            'sorotan_judul' => ['nullable', 'string', 'max:100'],
            'sorotan_sub' => ['nullable', 'string', 'max:200'],
            'galeri_judul' => ['nullable', 'string', 'max:100'],
            'galeri_sub' => ['nullable', 'string', 'max:200'],
            'grafik_judul' => ['nullable', 'string', 'max:100'],
            'grafik_sub' => ['nullable', 'string', 'max:200'],
            'cta_judul' => ['nullable', 'string', 'max:100'],
            'cta_deskripsi' => ['nullable', 'string', 'max:200'],
            'cta_tombol' => ['nullable', 'string', 'max:50'],

            // Footer
            'footer_deskripsi' => ['nullable', 'string', 'max:300'],
            'footer_kontak1' => ['nullable', 'string', 'max:200'],
            'footer_kontak2' => ['nullable', 'string', 'max:200'],
            'footer_link_label' => ['nullable', 'string', 'max:100'],
            'footer_link_url' => ['nullable', 'url', 'max:255'],

            // Halaman Tentang
            'tentang_p1' => ['nullable', 'string', 'max:2000'],
            'tentang_p2' => ['nullable', 'string', 'max:2000'],
            'tentang_kontak1' => ['nullable', 'string', 'max:300'],
            'tentang_kontak2' => ['nullable', 'string', 'max:300'],
        ]);

        foreach (Setting::BAWAAN as $kunci => $bawaan) {
            if (in_array($kunci, self::SAKELAR)) {
                Setting::set($kunci, $request->boolean($kunci) ? '1' : '0');

                continue;
            }

            if (in_array($kunci, self::BERKAS)) {
                if ($request->hasFile($kunci)) {
                    Setting::set($kunci, $request->file($kunci)->store('pengaturan', 'public'));
                } elseif ($request->boolean('hapus_'.$kunci)) {
                    Setting::set($kunci, '');
                }

                continue;
            }

            if (array_key_exists($kunci, $data)) {
                Setting::set($kunci, $data[$kunci] === null ? '' : (string) $data[$kunci]);
            }
        }

        return redirect()->route('admin.pengaturan.edit')
            ->with('sukses', 'Pengaturan tersimpan. Perubahan langsung berlaku di halaman depan.');
    }
}
