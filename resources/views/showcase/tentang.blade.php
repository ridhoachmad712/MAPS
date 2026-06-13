@extends('layouts.publik')

@section('judul', 'Tentang MAPS')

@section('deskripsi', 'Tentang MAPS — sistem arsip dan showcase portofolio capaian mahasiswa Program Studi Manajemen FEB Universitas Negeri Makassar, beserta alur verifikasi datanya.')

@section('konten')
    <div class="container-narrow container-xl py-5" style="max-width: 48rem;">

        <div class="text-center">
            <img src="{{ \App\Models\Setting::get('logo') ? asset('storage/'.\App\Models\Setting::get('logo')) : asset('favicon.svg') }}"
                 alt="Logo {{ \App\Models\Setting::get('nama_aplikasi') }}" class="rounded" style="height: 64px; width: auto;">
            <h1 class="h1 mt-3 mb-1">Tentang {{ \App\Models\Setting::get('nama_aplikasi') }}</h1>
            <p class="text-secondary">{{ \App\Models\Setting::get('tagline') }}</p>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <p>{{ \App\Models\Setting::get('tentang_p1') }}</p>
                <p class="mb-0">{{ \App\Models\Setting::get('tentang_p2') }}</p>
            </div>
        </div>

        {{-- Alur verifikasi --}}
        <h2 class="h2 mt-5 mb-1">Bagaimana Data Diverifikasi?</h2>
        <p class="text-secondary">Setiap entri melewati empat tahap sebelum tampil di halaman publik.</p>

        <div class="row row-cards mt-1">
            <div class="col-12 col-sm-6 d-flex">
                <div class="card w-100">
                    <div class="card-body">
                        <span class="avatar rounded-circle bg-blue-lt fw-bold">1</span>
                        <h3 class="mt-3 mb-1">Mahasiswa mengarsipkan</h3>
                        <p class="text-secondary mb-0">
                            Mahasiswa menginput capaiannya sendiri lengkap dengan berkas bukti
                            (sertifikat, SK, atau dokumen pendukung lain).
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 d-flex">
                <div class="card w-100">
                    <div class="card-body">
                        <span class="avatar rounded-circle bg-blue-lt fw-bold">2</span>
                        <h3 class="mt-3 mb-1">Prodi memeriksa</h3>
                        <p class="text-secondary mb-0">
                            Verifikator (dosen/admin prodi) memeriksa keabsahan bukti. Entri yang
                            tidak lengkap dikembalikan untuk diperbaiki.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 d-flex">
                <div class="card w-100">
                    <div class="card-body">
                        <span class="avatar rounded-circle bg-blue-lt fw-bold">3</span>
                        <h3 class="mt-3 mb-1">Terverifikasi resmi</h3>
                        <p class="text-secondary mb-0">
                            Entri yang sah berstatus terverifikasi dan masuk dalam statistik resmi
                            program studi — termasuk untuk kebutuhan akreditasi.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 d-flex">
                <div class="card w-100">
                    <div class="card-body">
                        <span class="avatar rounded-circle bg-blue-lt fw-bold">4</span>
                        <h3 class="mt-3 mb-1">Tampil dengan persetujuan</h3>
                        <p class="text-secondary mb-0">
                            Capaian tampil di halaman publik hanya jika mahasiswa memberikan
                            persetujuan. NIM selalu disamarkan demi privasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kontak --}}
        <h2 class="h2 mt-5 mb-3">Kontak</h2>
        <div class="card">
            <div class="card-body">
                <ul class="list-unstyled d-grid gap-3 mb-0">
                    <li class="d-flex gap-3">
                        <i class="bi bi-building text-secondary"></i>
                        {{ \App\Models\Setting::get('tentang_kontak1') }}
                    </li>
                    <li class="d-flex gap-3">
                        <i class="bi bi-envelope text-secondary"></i>
                        {{ \App\Models\Setting::get('tentang_kontak2') }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('showcase.capaian') }}" class="btn btn-primary">
                <i class="bi bi-table me-1"></i>Jelajahi Data Capaian
            </a>
        </div>
    </div>
@endsection
