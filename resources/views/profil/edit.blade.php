@extends('layouts.app')

@section('judul', 'Profil Saya')

@section('konten')
    <div class="page-header mb-4">
        <h1 class="page-title">Profil Saya</h1>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-person-circle me-2"></i>Data Profil</h3></div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        @if ($mahasiswa->foto)
                            <span class="avatar avatar-xl rounded-circle" style="background-image: url('{{ asset('storage/'.$mahasiswa->foto) }}')"></span>
                        @else
                            <span class="avatar avatar-xl rounded-circle">{{ strtoupper(substr($mahasiswa->nama_lengkap, 0, 1)) }}</span>
                        @endif
                        <div>
                            <div class="h3 mb-0">{{ $mahasiswa->nama_lengkap }}</div>
                            <div class="text-secondary">{{ $mahasiswa->nim }} · Angkatan {{ $mahasiswa->angkatan }}</div>
                            <div class="text-secondary small">{{ $mahasiswa->program_studi }}, FEB UNM</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" class="d-grid gap-3">
                        @csrf @method('PUT')
                        <div>
                            <label class="form-label">Ganti Foto Profil (JPG/PNG, maks 2 MB)</label>
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                            @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="konsen_publik" value="1" @checked($mahasiswa->konsen_publik)>
                            <span class="form-check-label">
                                <strong>Persetujuan tampil publik</strong><br>
                                <span class="text-secondary">Izinkan nama dan capaian terverifikasi saya ditampilkan di halaman showcase publik.</span>
                            </span>
                        </label>
                        <div>
                            <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-shield-lock me-2"></i>Ganti Kata Sandi</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profil.password') }}" class="d-grid gap-3">
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
                        <div>
                            <button class="btn btn-outline-secondary"><i class="bi bi-key me-1"></i>Ganti Kata Sandi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
