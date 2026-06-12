@extends('layouts.app')

@section('judul', 'Tambah Mahasiswa')

@section('konten')
    <h1 class="mb-6 text-xl font-extrabold text-navy-700">Tambah Akun Mahasiswa</h1>

    <div class="card">
        <div class="card-body p-6">
            <form method="POST" action="{{ route('admin.mahasiswa.store') }}">
                @csrf
                <div class="grid gap-4 md:grid-cols-12">
                    <div class="md:col-span-4">
                        <label class="form-label">NIM <span class="text-red-600">*</span></label>
                        <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" required>
                        @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">NIM juga menjadi username untuk masuk.</div>
                    </div>
                    <div class="md:col-span-8">
                        <label class="form-label">Nama Lengkap <span class="text-red-600">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" required>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="md:col-span-4">
                        <label class="form-label">Angkatan <span class="text-red-600">*</span></label>
                        <input type="number" name="angkatan" min="2000" max="2100" class="form-control @error('angkatan') is-invalid @enderror" value="{{ old('angkatan', date('Y')) }}" required>
                        @error('angkatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="md:col-span-8">
                        <label class="form-label">Program Studi <span class="text-red-600">*</span></label>
                        <input type="text" name="program_studi" class="form-control" value="{{ old('program_studi', 'Manajemen') }}" required>
                    </div>
                    <div class="md:col-span-6">
                        <label class="form-label">Email <span class="text-red-600">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="md:col-span-6">
                        <label class="form-label">Kata Sandi Awal <span class="text-red-600">*</span></label>
                        <input type="text" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Minimal 8 karakter. Sampaikan ke mahasiswa untuk diganti setelah masuk pertama.</div>
                    </div>
                </div>
                <hr class="my-5 border-slate-100">
                <div class="flex items-center gap-2">
                    <button class="btn btn-maps"><i class="bi bi-person-plus"></i>Buat Akun</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="px-3 text-sm text-slate-500 hover:text-slate-700">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
