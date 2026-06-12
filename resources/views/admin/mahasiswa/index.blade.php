@extends('layouts.app')

@section('judul', 'Data Mahasiswa')

@section('konten')
    <div class="page-header mb-4">
        <div class="row align-items-center g-2">
            <div class="col">
                <h1 class="page-title">Data Mahasiswa</h1>
            </div>
            <div class="col-auto d-flex gap-2">
                @if (Route::has('admin.mahasiswa.import.form'))
                    <a href="{{ route('admin.mahasiswa.import.form') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-file-earmark-arrow-up me-1"></i>Impor CSV
                    </a>
                @endif
                <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Tambah Mahasiswa</a>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <form method="GET" class="card-body row g-3 align-items-end">
            <div class="col-12 col-sm-4">
                <label class="form-label">Pencarian</label>
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Nama atau NIM..." value="{{ request('q') }}">
            </div>
            <div class="col-6 col-sm-3">
                <label class="form-label">Angkatan</label>
                <select name="angkatan" class="form-select form-select-sm">
                    <option value="">Semua angkatan</option>
                    @foreach ($daftarAngkatan as $a)
                        <option value="{{ $a }}" @selected(request('angkatan') == $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Saring</button>
                <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-sm btn-outline-secondary">Atur Ulang</a>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Angkatan</th>
                        <th>Email</th>
                        <th>Konsen Publik</th>
                        <th>Status Akun</th>
                        <th class="text-end">Capaian Terverifikasi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswa as $m)
                        <tr>
                            <td>{{ $m->nim }}</td>
                            <td class="fw-semibold">{{ $m->nama_lengkap }}</td>
                            <td>{{ $m->angkatan }}</td>
                            <td class="small">{{ $m->user->email ?? '—' }}</td>
                            <td>
                                @if ($m->konsen_publik)
                                    <span class="badge bg-success-lt">Setuju</span>
                                @else
                                    <span class="badge bg-secondary-lt">Tidak</span>
                                @endif
                            </td>
                            <td>
                                @if ($m->user?->is_active)
                                    <span class="badge bg-success-lt">Aktif</span>
                                @else
                                    <span class="badge bg-danger-lt">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end"><span class="badge bg-success-lt">{{ $m->total_terverifikasi }}</span></td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.mahasiswa.edit', $m) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.mahasiswa.destroy', $m) }}"
                                          onsubmit="return confirm('Hapus {{ $m->nama_lengkap }} beserta akun dan seluruh portofolionya? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger btn-icon" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-secondary py-5">Belum ada data mahasiswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($mahasiswa->hasPages())
            <div class="card-footer">{{ $mahasiswa->links() }}</div>
        @endif
    </div>
@endsection
