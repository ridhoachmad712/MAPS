@extends('layouts.publik')

@section('judul', 'Masuk')

@section('robots')
    <meta name="robots" content="noindex">
@endsection

@section('konten')
    <div class="container-xl py-5" style="max-width: 56rem;">
        <div class="card overflow-hidden">
            <div class="row g-0">

                <div class="col-12 col-lg-6 d-flex flex-column justify-content-between p-4 p-lg-5 text-white" style="background: var(--primer-700, var(--tblr-primary));">
                    <div>
                        <img src="{{ asset('favicon.svg') }}" alt="Logo MAPS" width="48" height="48" class="rounded border border-light-subtle">
                        <h1 class="h1 text-white mt-3 mb-1">MAPS</h1>
                        <p class="text-white opacity-75">Management Student Achievement Portfolio System</p>

                        <ul class="list-unstyled d-grid gap-3 mt-4">
                            <li class="d-flex gap-3">
                                <i class="bi bi-archive opacity-75"></i>
                                <span>Arsip portofolio capaian mahasiswa yang terpusat dan rapi</span>
                            </li>
                            <li class="d-flex gap-3">
                                <i class="bi bi-patch-check opacity-75"></i>
                                <span>Verifikasi resmi oleh program studi</span>
                            </li>
                            <li class="d-flex gap-3">
                                <i class="bi bi-globe2 opacity-75"></i>
                                <span>Showcase publik untuk capaian terverifikasi</span>
                            </li>
                        </ul>
                    </div>
                    <p class="small opacity-50 mt-5 mb-0">
                        Program Studi Manajemen<br>Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar
                    </p>
                </div>

                <div class="col-12 col-lg-6 p-4 p-lg-5">
                    <h2 class="h2 mb-1">Masuk ke akun Anda</h2>
                    <p class="text-secondary mb-4">Mahasiswa masuk memakai NIM sebagai nama pengguna.</p>

                    @include('partials.flash')

                    <form method="POST" action="{{ route('login.attempt') }}" class="d-grid gap-3">
                        @csrf
                        <div>
                            <label for="username" class="form-label">Nama Pengguna / Email</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                   id="username" name="username" value="{{ old('username') }}"
                                   placeholder="NIM untuk mahasiswa" required autofocus>
                        </div>
                        <div>
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                        </div>
                        <label class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="remember">
                            <span class="form-check-label">Ingat saya</span>
                        </label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                        </button>
                    </form>

                    <p class="text-center text-secondary small mt-4 mb-0">
                        Belum punya akun? Hubungi admin prodi untuk pendaftaran.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
