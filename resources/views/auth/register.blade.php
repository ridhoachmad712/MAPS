@extends('layouts.base')

@section('judul', 'Daftar Akun Mahasiswa')

@section('robots')
    <meta name="robots" content="noindex">
@endsection

{{-- Halaman daftar berdiri sendiri: tanpa navbar dan footer --}}
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
                <h1 class="h2 text-center mb-1">Daftar Akun Mahasiswa</h1>
                <p class="text-secondary text-center mb-4">Khusus mahasiswa Prodi Manajemen FEB UNM.</p>

                @include('partials.flash')

                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control @error('nim') is-invalid @enderror"
                                   id="nim" name="nim" value="{{ old('nim') }}" required autofocus>
                            @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="form-hint">NIM menjadi nama pengguna untuk masuk.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="angkatan" class="form-label">Angkatan</label>
                            <input type="number" class="form-control @error('angkatan') is-invalid @enderror"
                                   id="angkatan" name="angkatan" value="{{ old('angkatan', date('Y')) }}"
                                   min="2000" max="{{ date('Y') + 1 }}" required>
                            @error('angkatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                               id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="program_studi" class="form-label">Program Studi</label>
                        <input type="text" class="form-control @error('program_studi') is-invalid @enderror"
                               id="program_studi" name="program_studi" value="{{ old('program_studi', 'Manajemen') }}" required>
                        @error('program_studi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="form-hint">Minimal 8 karakter.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Ulangi Kata Sandi</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-1"></i>
                        Setelah mendaftar, akun Anda akan ditinjau admin prodi. Anda baru dapat masuk setelah disetujui.
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-person-plus me-1"></i>Daftar
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-secondary mt-3 mb-0">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </p>
    </div>
@endsection
