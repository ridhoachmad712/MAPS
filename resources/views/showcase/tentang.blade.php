@extends('layouts.publik')

@section('judul', 'Tentang MAPS')

@section('deskripsi', 'Tentang MAPS — sistem arsip dan showcase portofolio capaian mahasiswa Program Studi Manajemen FEB Universitas Negeri Makassar, beserta alur verifikasi datanya.')

@section('konten')
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">

        <div class="text-center">
            <img src="{{ asset('favicon.svg') }}" alt="Logo MAPS" class="mx-auto h-16 w-16 rounded-2xl">
            <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-navy-700 sm:text-3xl">Tentang MAPS</h1>
            <p class="mt-2 text-slate-500">Management Student Achievement Portfolio System</p>
        </div>

        <div class="card mt-8 p-6 sm:p-8">
            <p class="leading-relaxed text-slate-700">
                MAPS adalah sistem arsip dan etalase digital portofolio capaian mahasiswa
                <strong>Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar</strong>.
                Sistem ini mencatat dan mempublikasikan capaian mahasiswa dalam tujuh kategori:
                prestasi/kompetisi, Program Kreativitas Mahasiswa (PKM), organisasi, MBKM,
                sertifikasi, publikasi/karya ilmiah, dan dokumentasi kegiatan lainnya.
            </p>
            <p class="mt-4 leading-relaxed text-slate-700">
                Seluruh data yang tampil di halaman publik telah melalui proses verifikasi oleh
                program studi dan ditampilkan atas persetujuan mahasiswa yang bersangkutan.
                Statistik dihitung otomatis dari data — tidak ada angka yang diinput manual.
            </p>
        </div>

        {{-- Alur verifikasi --}}
        <h2 class="mt-10 text-lg font-extrabold text-navy-700">Bagaimana Data Diverifikasi?</h2>
        <p class="mt-1 text-sm text-slate-500">Setiap entri melewati empat tahap sebelum tampil di halaman publik.</p>

        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <div class="card p-5">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-navy-50 font-bold text-navy-600">1</span>
                <h3 class="mt-3 font-bold text-gray-900">Mahasiswa mengarsipkan</h3>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">
                    Mahasiswa menginput capaiannya sendiri lengkap dengan berkas bukti
                    (sertifikat, SK, atau dokumen pendukung lain).
                </p>
            </div>
            <div class="card p-5">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-navy-50 font-bold text-navy-600">2</span>
                <h3 class="mt-3 font-bold text-gray-900">Prodi memeriksa</h3>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">
                    Verifikator (dosen/admin prodi) memeriksa keabsahan bukti. Entri yang
                    tidak lengkap dikembalikan untuk diperbaiki.
                </p>
            </div>
            <div class="card p-5">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-navy-50 font-bold text-navy-600">3</span>
                <h3 class="mt-3 font-bold text-gray-900">Terverifikasi resmi</h3>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">
                    Entri yang sah berstatus terverifikasi dan masuk dalam statistik resmi
                    program studi — termasuk untuk kebutuhan akreditasi.
                </p>
            </div>
            <div class="card p-5">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-navy-50 font-bold text-navy-600">4</span>
                <h3 class="mt-3 font-bold text-gray-900">Tampil dengan persetujuan</h3>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">
                    Capaian tampil di halaman publik hanya jika mahasiswa memberikan
                    persetujuan. NIM selalu disamarkan demi privasi.
                </p>
            </div>
        </div>

        {{-- Kontak --}}
        <h2 class="mt-10 text-lg font-extrabold text-navy-700">Kontak</h2>
        <div class="card mt-4 p-6">
            <ul class="space-y-3 text-sm text-slate-700">
                <li class="flex items-start gap-3">
                    <i class="bi bi-building mt-0.5 text-navy-400"></i>
                    Program Studi Manajemen, Fakultas Ekonomi dan Bisnis,
                    Universitas Negeri Makassar — Kampus Gunung Sari, Makassar
                </li>
                <li class="flex items-start gap-3">
                    <i class="bi bi-envelope mt-0.5 text-navy-400"></i>
                    Untuk koreksi data atau pertanyaan, hubungi admin program studi.
                </li>
            </ul>
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('showcase.capaian') }}" class="btn btn-maps">
                <i class="bi bi-table"></i>Jelajahi Data Capaian
            </a>
        </div>
    </div>
@endsection
