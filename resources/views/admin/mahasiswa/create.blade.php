@extends('layouts.app')

@section('judul', 'Tambah Mahasiswa')

@section('konten')
    <div class="page-header mb-4">
        <h1 class="page-title">Tambah Akun Mahasiswa</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.mahasiswa.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label required">NIM</label>
                        <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" required>
                        @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-hint">NIM juga menjadi username untuk masuk.</div>
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" required>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label required">Angkatan</label>
                        <input type="number" name="angkatan" min="2000" max="2100" class="form-control @error('angkatan') is-invalid @enderror" value="{{ old('angkatan', date('Y')) }}" required>
                        @error('angkatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="form-label required">Program Studi</label>
                        <input type="text" name="program_studi" class="form-control" value="{{ old('program_studi', 'Manajemen') }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label required">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label required">Kata Sandi Awal</label>
                        <input type="text" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-hint">Minimal 8 karakter. Sampaikan ke mahasiswa untuk diganti setelah masuk pertama.</div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Buat Akun</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-link link-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
