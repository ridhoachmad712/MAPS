@extends('layouts.app')

@section('judul', 'Profil Saya')

@section('konten')
    <h1 class="mb-6 text-xl font-extrabold text-navy-700">Profil Saya</h1>

    <div class="grid gap-4 lg:grid-cols-12">
        <div class="lg:col-span-7">
            <div class="card">
                <div class="card-header"><i class="bi bi-person-circle"></i>Data Profil</div>
                <div class="card-body p-6">
                    <div class="mb-6 flex items-center gap-4">
                        @if ($mahasiswa->foto)
                            <img src="{{ asset('storage/'.$mahasiswa->foto) }}" alt="Foto profil" class="foto-avatar-besar">
                        @else
                            <span class="avatar-inisial-besar">{{ strtoupper(substr($mahasiswa->nama_lengkap, 0, 1)) }}</span>
                        @endif
                        <div>
                            <div class="text-lg font-bold text-navy-700">{{ $mahasiswa->nama_lengkap }}</div>
                            <div class="text-sm text-slate-500">{{ $mahasiswa->nim }} · Angkatan {{ $mahasiswa->angkatan }}</div>
                            <div class="text-xs text-slate-400">{{ $mahasiswa->program_studi }}, FEB UNM</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="form-label">Ganti Foto Profil (JPG/PNG, maks 2 MB)</label>
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                            @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label class="flex items-start gap-2.5">
                            <input class="form-check-input mt-1" type="checkbox" name="konsen_publik" value="1" @checked($mahasiswa->konsen_publik)>
                            <span class="text-sm">
                                <strong>Persetujuan tampil publik</strong><br>
                                <span class="text-slate-500">Izinkan nama dan capaian terverifikasi saya ditampilkan di halaman showcase publik.</span>
                            </span>
                        </label>
                        <button class="btn btn-maps"><i class="bi bi-save"></i>Simpan Profil</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="card">
                <div class="card-header"><i class="bi bi-shield-lock"></i>Ganti Kata Sandi</div>
                <div class="card-body p-6">
                    <form method="POST" action="{{ route('profil.password') }}" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="form-label">Kata Sandi Lama</label>
                            <input type="password" name="password_lama" class="form-control @error('password_lama') is-invalid @enderror" required>
                            @error('password_lama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Kata Sandi Baru (min. 8 karakter)</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Ulangi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button class="btn btn-outline"><i class="bi bi-key"></i>Ganti Kata Sandi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
