@extends('layouts.app')

@section('judul', 'Impor Mahasiswa')

@section('konten')
    <ol class="breadcrumb mb-3" aria-label="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('admin.mahasiswa.index') }}">Data Mahasiswa</a></li>
        <li class="breadcrumb-item active" aria-current="page">Impor CSV</li>
    </ol>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-file-earmark-arrow-up me-2"></i>Impor Mahasiswa dari CSV</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.mahasiswa.import') }}" enctype="multipart/form-data" class="d-grid gap-3">
                        @csrf
                        <div>
                            <label class="form-label required">Berkas CSV (maks 5 MB)</label>
                            <input type="file" name="berkas" class="form-control @error('berkas') is-invalid @enderror" accept=".csv,.txt" required>
                            @error('berkas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-hint">Baris pertama harus berisi nama kolom.</div>
                        </div>
                        <div>
                            <button class="btn btn-primary"><i class="bi bi-upload me-1"></i>Impor Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-info-circle me-2"></i>Format Berkas</h3></div>
                <div class="card-body">
                    <p class="mb-2">Kolom yang dikenali (huruf besar/kecil bebas, spasi boleh):</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-vcenter">
                            <thead>
                                <tr><th>Kolom</th><th>Wajib</th><th>Keterangan</th></tr>
                            </thead>
                            <tbody>
                                <tr><td><code>nim</code></td><td><span class="badge bg-danger-lt">wajib</span></td><td>maks 20 karakter, unik</td></tr>
                                <tr><td><code>nama_lengkap</code></td><td><span class="badge bg-danger-lt">wajib</span></td><td>maks 255 karakter</td></tr>
                                <tr><td><code>angkatan</code></td><td><span class="badge bg-danger-lt">wajib</span></td><td>angka 2000–2100</td></tr>
                                <tr><td><code>program_studi</code></td><td><span class="badge bg-secondary-lt">opsional</span></td><td>kosong = "Manajemen"</td></tr>
                                <tr><td><code>email</code></td><td><span class="badge bg-secondary-lt">opsional</span></td><td>kosong = NIM@student.unm.ac.id</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <ul class="text-secondary mb-0 ps-3">
                        <li>Satu NIM satu baris — NIM yang sudah ada <strong>memperbarui</strong> data, bukan menduplikasi.</li>
                        <li>Akun login dibuat otomatis: username = NIM, kata sandi awal = NIM.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
