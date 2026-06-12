@extends('layouts.publik')

@section('judul', 'Masuk')

@section('konten')
    <div class="mx-auto w-full max-w-4xl px-4 py-12">
        <div class="card grid overflow-hidden lg:grid-cols-2">

            <div class="flex flex-col justify-between bg-navy-700 p-8 text-white">
                <div>
                    <img src="{{ asset('favicon.svg') }}" alt="Logo MAPS" class="h-12 w-12 rounded-xl border border-white/20">
                    <h1 class="mt-4 text-2xl font-extrabold">MAPS</h1>
                    <p class="mt-1 text-sm text-white/70">Management Student Achievement Portfolio System</p>

                    <ul class="mt-8 space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="bi bi-archive mt-0.5 text-white/60"></i>
                            <span>Arsip portofolio capaian mahasiswa yang terpusat dan rapi</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-patch-check mt-0.5 text-white/60"></i>
                            <span>Verifikasi resmi oleh program studi</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-globe2 mt-0.5 text-white/60"></i>
                            <span>Showcase publik untuk capaian terverifikasi</span>
                        </li>
                    </ul>
                </div>
                <p class="mt-10 text-xs text-white/50">
                    Program Studi Manajemen<br>Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar
                </p>
            </div>

            <div class="p-8">
                <h2 class="text-lg font-extrabold text-navy-700">Masuk ke akun Anda</h2>
                <p class="mb-6 mt-1 text-sm text-slate-500">Mahasiswa masuk memakai NIM sebagai nama pengguna.</p>

                @include('partials.flash')

                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
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
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input class="form-check-input" type="checkbox" name="remember">
                        Ingat saya
                    </label>
                    <button type="submit" class="btn btn-maps w-full">
                        <i class="bi bi-box-arrow-in-right"></i>Masuk
                    </button>
                </form>

                <p class="mt-6 text-center text-xs text-slate-400">
                    Belum punya akun? Hubungi admin prodi untuk pendaftaran.
                </p>
            </div>
        </div>
    </div>
@endsection
