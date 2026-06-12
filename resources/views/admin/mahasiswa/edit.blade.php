@extends('layouts.app')

@section('judul', 'Ubah Mahasiswa')

@section('konten')
    <div class="page-header mb-4">
        <h1 class="page-title">Ubah Data Mahasiswa</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswa) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label required">NIM</label>
                        <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim', $mahasiswa->nim) }}" required>
                        @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" required>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label required">Angkatan</label>
                        <input type="number" name="angkatan" min="2000" max="2100" class="form-control" value="{{ old('angkatan', $mahasiswa->angkatan) }}" required>
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="form-label required">Program Studi</label>
                        <input type="text" name="program_studi" class="form-control" value="{{ old('program_studi', $mahasiswa->program_studi) }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label required">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $mahasiswa->user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Kata Sandi Baru <span class="form-label-description">(kosongkan jika tidak diganti)</span></label>
                        <input type="text" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   @checked(old('is_active', $mahasiswa->user->is_active))>
                            <span class="form-check-label">Akun aktif (dapat masuk ke sistem)</span>
                        </label>
                    </div>
                </div>
                <hr class="my-4">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-link link-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
