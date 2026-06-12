@extends('layouts.app')

@section('judul', 'Pengaturan Tampilan')

@section('konten')
    <div class="page-header mb-4">
        <h1 class="page-title">Pengaturan Tampilan</h1>
        <p class="text-secondary mb-0">Kustomisasi identitas, warna, dan isi halaman depan — berlaku langsung tanpa build ulang.</p>
    </div>

    <form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data" class="d-grid gap-3">
        @csrf @method('PUT')

        {{-- Identitas --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title mb-0">Identitas</h3>
                    <p class="card-subtitle mb-0">Nama dan logo yang tampil di navbar, footer, login, dan tab browser.</p>
                </div>
            </div>
            <div class="card-body row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label required">Nama Aplikasi</label>
                    <input type="text" name="nama_aplikasi" maxlength="50" class="form-control @error('nama_aplikasi') is-invalid @enderror"
                           value="{{ old('nama_aplikasi', $nilai['nama_aplikasi']) }}" required>
                    @error('nama_aplikasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label required">Nama Prodi/Pemilik</label>
                    <input type="text" name="nama_pemilik" maxlength="100" class="form-control @error('nama_pemilik') is-invalid @enderror"
                           value="{{ old('nama_pemilik', $nilai['nama_pemilik']) }}" required>
                    @error('nama_pemilik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-hint">Baris kedua pada brand navbar.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Tagline</label>
                    <input type="text" name="tagline" maxlength="150" class="form-control" value="{{ old('tagline', $nilai['tagline']) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Logo</label>
                    @if ($nilai['logo'])
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <img src="{{ asset('storage/'.$nilai['logo']) }}" alt="Logo saat ini" width="48" height="48" class="rounded border">
                            <label class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="hapus_logo" value="1">
                                <span class="form-check-label">Hapus logo, pakai logo bawaan berwarna tema</span>
                            </label>
                        </div>
                    @endif
                    <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept=".png,.jpg,.jpeg,.svg,.webp">
                    @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-hint">PNG/JPG/SVG maks 2 MB. Kosongkan untuk mempertahankan logo saat ini.</div>
                </div>
            </div>
        </div>

        {{-- Warna --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title mb-0">Warna</h3>
                    <p class="card-subtitle mb-0">Berlaku langsung tanpa build ulang. Warna teks menyesuaikan kontras secara otomatis.</p>
                </div>
            </div>
            <div class="card-body row g-3">
                <div class="col-6 col-md-3">
                    <label class="form-label required">Primer (tombol &amp; aksen)</label>
                    <input type="color" name="warna_primer" class="form-control form-control-color w-100 @error('warna_primer') is-invalid @enderror"
                           value="{{ old('warna_primer', $nilai['warna_primer']) }}" required>
                    @error('warna_primer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label required">Hero (latar gradasi)</label>
                    <input type="color" name="warna_hero" class="form-control form-control-color w-100 @error('warna_hero') is-invalid @enderror"
                           value="{{ old('warna_hero', $nilai['warna_hero']) }}" required>
                    @error('warna_hero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label required">Navbar</label>
                    <input type="color" name="warna_navbar" class="form-control form-control-color w-100 @error('warna_navbar') is-invalid @enderror"
                           value="{{ old('warna_navbar', $nilai['warna_navbar']) }}" required>
                    @error('warna_navbar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label required">Footer</label>
                    <input type="color" name="warna_footer" class="form-control form-control-color w-100 @error('warna_footer') is-invalid @enderror"
                           value="{{ old('warna_footer', $nilai['warna_footer']) }}" required>
                    @error('warna_footer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Hero Beranda --}}
        <div class="card">
            <div class="card-header"><h3 class="card-title">Hero Beranda</h3></div>
            <div class="card-body row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Teks Kecil di Atas Judul</label>
                    <input type="text" name="hero_eyebrow" maxlength="150" class="form-control" value="{{ old('hero_eyebrow', $nilai['hero_eyebrow']) }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label required">Judul Utama</label>
                    <input type="text" name="hero_judul" maxlength="100" class="form-control @error('hero_judul') is-invalid @enderror"
                           value="{{ old('hero_judul', $nilai['hero_judul']) }}" required>
                    @error('hero_judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="hero_deskripsi" rows="2" maxlength="300" class="form-control">{{ old('hero_deskripsi', $nilai['hero_deskripsi']) }}</textarea>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Placeholder Kotak Pencarian</label>
                    <input type="text" name="hero_placeholder" maxlength="150" class="form-control" value="{{ old('hero_placeholder', $nilai['hero_placeholder']) }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Ketebalan Overlay Foto (%)</label>
                    <input type="number" name="hero_overlay" min="0" max="95" class="form-control @error('hero_overlay') is-invalid @enderror"
                           value="{{ old('hero_overlay', $nilai['hero_overlay']) }}">
                    @error('hero_overlay')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-hint">Hanya berlaku bila foto latar diunggah.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Foto Latar Hero</label>
                    @if ($nilai['hero_foto'])
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <img src="{{ asset('storage/'.$nilai['hero_foto']) }}" alt="Foto hero saat ini" class="rounded border" style="height: 3rem;">
                            <label class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="hapus_hero_foto" value="1">
                                <span class="form-check-label">Hapus foto, pakai gradasi warna hero</span>
                            </label>
                        </div>
                    @endif
                    <input type="file" name="hero_foto" class="form-control @error('hero_foto') is-invalid @enderror" accept=".png,.jpg,.jpeg,.webp">
                    @error('hero_foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-hint">Maks 4 MB. Kosongkan untuk memakai gradasi warna hero.</div>
                </div>
            </div>
        </div>

        {{-- Seksi Beranda --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title mb-0">Seksi Beranda</h3>
                    <p class="card-subtitle mb-0">Atur tampil/sembunyi serta judul tiap seksi.</p>
                </div>
            </div>
            <div class="card-body row g-3">
                <div class="col-12 col-md-6 d-grid gap-3">
                    <label class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="sorotan_tampil" value="1" @checked(old('sorotan_tampil', $nilai['sorotan_tampil']) == '1')>
                        <span class="form-check-label">Tampilkan Sorotan Capaian</span>
                    </label>
                    <div>
                        <label class="form-label">Judul Sorotan</label>
                        <input type="text" name="sorotan_judul" maxlength="100" class="form-control" value="{{ old('sorotan_judul', $nilai['sorotan_judul']) }}">
                    </div>
                    <div>
                        <label class="form-label">Subjudul Sorotan</label>
                        <input type="text" name="sorotan_sub" maxlength="200" class="form-control" value="{{ old('sorotan_sub', $nilai['sorotan_sub']) }}">
                    </div>
                </div>
                <div class="col-12 col-md-6 d-grid gap-3">
                    <label class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="galeri_tampil" value="1" @checked(old('galeri_tampil', $nilai['galeri_tampil']) == '1')>
                        <span class="form-check-label">Tampilkan Mahasiswa Berprestasi</span>
                    </label>
                    <div>
                        <label class="form-label">Judul Galeri</label>
                        <input type="text" name="galeri_judul" maxlength="100" class="form-control" value="{{ old('galeri_judul', $nilai['galeri_judul']) }}">
                    </div>
                    <div>
                        <label class="form-label">Subjudul Galeri</label>
                        <input type="text" name="galeri_sub" maxlength="200" class="form-control" value="{{ old('galeri_sub', $nilai['galeri_sub']) }}">
                    </div>
                </div>
                <div class="col-12 col-md-6 d-grid gap-3">
                    <label class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="grafik_tampil" value="1" @checked(old('grafik_tampil', $nilai['grafik_tampil']) == '1')>
                        <span class="form-check-label">Tampilkan Sekilas Statistik</span>
                    </label>
                    <div>
                        <label class="form-label">Judul Statistik</label>
                        <input type="text" name="grafik_judul" maxlength="100" class="form-control" value="{{ old('grafik_judul', $nilai['grafik_judul']) }}">
                    </div>
                    <div>
                        <label class="form-label">Subjudul Statistik</label>
                        <input type="text" name="grafik_sub" maxlength="200" class="form-control" value="{{ old('grafik_sub', $nilai['grafik_sub']) }}">
                    </div>
                </div>
                <div class="col-12 col-md-6 d-grid gap-3">
                    <label class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="cta_tampil" value="1" @checked(old('cta_tampil', $nilai['cta_tampil']) == '1')>
                        <span class="form-check-label">Tampilkan Kartu Ajakan</span>
                    </label>
                    <div>
                        <label class="form-label">Judul Kartu Ajakan</label>
                        <input type="text" name="cta_judul" maxlength="100" class="form-control" value="{{ old('cta_judul', $nilai['cta_judul']) }}">
                    </div>
                    <div>
                        <label class="form-label">Deskripsi Kartu Ajakan</label>
                        <input type="text" name="cta_deskripsi" maxlength="200" class="form-control" value="{{ old('cta_deskripsi', $nilai['cta_deskripsi']) }}">
                    </div>
                    <div>
                        <label class="form-label">Teks Tombol Ajakan</label>
                        <input type="text" name="cta_tombol" maxlength="50" class="form-control" value="{{ old('cta_tombol', $nilai['cta_tombol']) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="card">
            <div class="card-header"><h3 class="card-title">Footer</h3></div>
            <div class="card-body row g-3">
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="footer_deskripsi" rows="2" maxlength="300" class="form-control">{{ old('footer_deskripsi', $nilai['footer_deskripsi']) }}</textarea>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Kontak Baris 1</label>
                    <input type="text" name="footer_kontak1" maxlength="200" class="form-control" value="{{ old('footer_kontak1', $nilai['footer_kontak1']) }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Kontak Baris 2</label>
                    <input type="text" name="footer_kontak2" maxlength="200" class="form-control" value="{{ old('footer_kontak2', $nilai['footer_kontak2']) }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Label Tautan Eksternal</label>
                    <input type="text" name="footer_link_label" maxlength="100" class="form-control" value="{{ old('footer_link_label', $nilai['footer_link_label']) }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">URL Tautan Eksternal</label>
                    <input type="url" name="footer_link_url" maxlength="255" class="form-control @error('footer_link_url') is-invalid @enderror"
                           value="{{ old('footer_link_url', $nilai['footer_link_url']) }}">
                    @error('footer_link_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Halaman Tentang --}}
        <div class="card">
            <div class="card-header"><h3 class="card-title">Halaman Tentang</h3></div>
            <div class="card-body row g-3">
                <div class="col-12">
                    <label class="form-label">Paragraf 1</label>
                    <textarea name="tentang_p1" rows="4" maxlength="2000" class="form-control">{{ old('tentang_p1', $nilai['tentang_p1']) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Paragraf 2</label>
                    <textarea name="tentang_p2" rows="3" maxlength="2000" class="form-control">{{ old('tentang_p2', $nilai['tentang_p2']) }}</textarea>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Kontak Baris 1</label>
                    <input type="text" name="tentang_kontak1" maxlength="300" class="form-control" value="{{ old('tentang_kontak1', $nilai['tentang_kontak1']) }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Kontak Baris 2</label>
                    <input type="text" name="tentang_kontak2" maxlength="300" class="form-control" value="{{ old('tentang_kontak2', $nilai['tentang_kontak2']) }}">
                </div>
            </div>
        </div>

        <div>
            <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Pengaturan</button>
        </div>
    </form>
@endsection
