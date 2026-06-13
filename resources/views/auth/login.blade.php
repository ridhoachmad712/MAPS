@extends('layouts.base')

@section('judul', 'Masuk')

@section('robots')
    <meta name="robots" content="noindex">
@endsection

{{-- Halaman masuk berdiri sendiri: tanpa navbar dan footer --}}
@section('tanpa_footer', '1')

@section('isi')
    <div class="container-tight d-flex flex-column justify-content-center py-4" style="min-height: 100vh;">
        <div class="text-center mb-4">
            <a href="{{ route('showcase.index') }}" title="Kembali ke beranda">
                <img src="{{ \App\Models\Setting::get('logo') ? asset('storage/'.\App\Models\Setting::get('logo')) : asset('favicon.svg') }}"
                     alt="Logo {{ \App\Models\Setting::get('nama_aplikasi') }}" class="rounded" style="height: 56px; width: auto;">
            </a>
        </div>

        <div class="card card-md">
            <div class="card-body">
                <h1 class="h2 text-center mb-1">Masuk ke {{ \App\Models\Setting::get('nama_aplikasi') }}</h1>
                <p class="text-secondary text-center mb-4">Mahasiswa masuk memakai NIM sebagai nama pengguna.</p>

                @include('partials.flash')

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Nama Pengguna / Email</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                               id="username" name="username" value="{{ old('username') }}"
                               placeholder="NIM untuk mahasiswa" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                    </div>
                    <label class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember">
                        <span class="form-check-label">Ingat saya</span>
                    </label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-secondary mt-3 mb-0">
            Belum punya akun? Hubungi admin prodi untuk pendaftaran.
        </p>
    </div>
@endsection
