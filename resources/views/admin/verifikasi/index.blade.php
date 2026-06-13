@extends('layouts.app')

@section('judul', 'Antrian Verifikasi')

@section('konten')
    <div class="page-header mb-4">
        <div class="row align-items-center g-2">
            <div class="col">
                <h1 class="page-title">Antrian Verifikasi</h1>
            </div>
            <div class="col-auto">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="q" class="form-control form-control-sm" style="width: 16rem;"
                           placeholder="Cari judul / nama / NIM..." value="{{ request('q') }}">
                    <button class="btn btn-sm btn-primary btn-icon" aria-label="Cari"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Judul</th>
                        <th class="d-none d-lg-table-cell">Kategori</th>
                        <th class="d-none d-md-table-cell">Tahun</th>
                        <th class="d-none d-md-table-cell">Level</th>
                        <th class="d-none d-lg-table-cell">Bukti</th>
                        <th class="d-none d-lg-table-cell">Diajukan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($antrian as $p)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <div class="text-secondary small">{{ $p->mahasiswa->nim }} · {{ $p->mahasiswa->angkatan }}</div>
                            </td>
                            <td>{{ $p->judul }}</td>
                            <td class="d-none d-lg-table-cell"><span class="badge bg-secondary-lt">{{ $p->kategori->kode }}</span></td>
                            <td class="d-none d-md-table-cell">{{ $p->tahun_pencapaian }}</td>
                            <td class="d-none d-md-table-cell"><span class="badge bg-{{ $p->levelBadge() }}-lt">{{ $p->levelLabel() }}</span></td>
                            <td class="d-none d-lg-table-cell">{{ $p->bukti->count() }} berkas</td>
                            <td class="d-none d-lg-table-cell text-secondary small">{{ $p->updated_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.verifikasi.show', $p) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-search me-1"></i>Periksa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-secondary py-5">
                                <i class="bi bi-check2-circle d-block fs-1 text-success mb-2"></i>
                                Tidak ada entri yang menunggu verifikasi. Kerja bagus!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($antrian->hasPages())
            <div class="card-footer">{{ $antrian->links() }}</div>
        @endif
    </div>
@endsection
