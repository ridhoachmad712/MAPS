@extends('layouts.app')

@section('judul', 'Ubah Mahasiswa')

@section('konten')
    <h1 class="mb-6 text-xl font-extrabold text-navy-700">Ubah Data Mahasiswa</h1>

    <div class="card">
        <div class="card-body p-6">
            <form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswa) }}">
                @csrf @method('PUT')
                <div class="grid gap-4 md:grid-cols-12">
                    <div class="md:col-span-4">
                        <label class="form-label">NIM <span class="text-red-600">*</span></label>
                        <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim', $mahasiswa->nim) }}" required>
                        @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="md:col-span-8">
                        <label class="form-label">Nama Lengkap <span class="text-red-600">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" required>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="md:col-span-4">
                        <label class="form-label">Angkatan <span class="text-red-600">*</span></label>
                        <input type="number" name="angkatan" min="2000" max="2100" class="form-control" value="{{ old('angkatan', $mahasiswa->angkatan) }}" required>
                    </div>
                    <div class="md:col-span-8">
                        <label class="form-label">Program Studi <span class="text-red-600">*</span></label>
                        <input type="text" name="program_studi" class="form-control" value="{{ old('program_studi', $mahasiswa->program_studi) }}" required>
                    </div>
                    <div class="md:col-span-6">
                        <label class="form-label">Email <span class="text-red-600">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $mahasiswa->user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="md:col-span-6">
                        <label class="form-label">Kata Sandi Baru <span class="font-normal text-slate-400">(kosongkan jika tidak diganti)</span></label>
                        <input type="text" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="md:col-span-12">
                        <label class="flex items-center gap-2.5 text-sm">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   @checked(old('is_active', $mahasiswa->user->is_active))>
                            Akun aktif (dapat masuk ke sistem)
                        </label>
                    </div>
                </div>
                <hr class="my-5 border-slate-100">
                <div class="flex items-center gap-2">
                    <button class="btn btn-maps"><i class="bi bi-save"></i>Simpan Perubahan</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="px-3 text-sm text-slate-500 hover:text-slate-700">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
