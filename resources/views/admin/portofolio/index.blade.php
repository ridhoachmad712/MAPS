@extends('layouts.app')

@section('judul', 'Semua Portofolio')

@section('konten')
    <div class="page-header mb-4">
        <div class="row align-items-center g-2">
            <div class="col">
                <h1 class="page-title">Semua Portofolio</h1>
            </div>
            @if (Route::has('admin.portofolio.export'))
                <div class="col-auto">
                    <a href="{{ route('admin.portofolio.export', request()->query()) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i>Ekspor Laporan (CSV)
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="card mb-3">
        <form method="GET" class="card-body row g-3 align-items-end">
            <div class="col-12 col-sm-3">
                <label class="form-label">Pencarian</label>
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Judul / nama / NIM..." value="{{ request('q') }}">
            </div>
            <div class="col-6 col-sm-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach (\App\Models\Portofolio::STATUS_LABEL as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(request('status') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-1">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->kategori_id }}" @selected(request('kategori') == $k->kategori_id)>{{ $k->kode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-2">
                <label class="form-label">Level</label>
                <select name="level" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach (\App\Models\Portofolio::LEVEL_LABEL as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(request('level') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-1">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach ($daftarTahun as $t)
                        <option value="{{ $t }}" @selected(request('tahun') == $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-1">
                <label class="form-label">Angkatan</label>
                <select name="angkatan" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach ($daftarAngkatan as $a)
                        <option value="{{ $a }}" @selected(request('angkatan') == $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Saring</button>
                <a href="{{ route('admin.portofolio.index') }}" class="btn btn-sm btn-outline-secondary">Atur Ulang</a>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Publik</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($portofolio as $p)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <div class="text-secondary small">{{ $p->mahasiswa->nim }} · {{ $p->mahasiswa->angkatan }}</div>
                            </td>
                            <td>{{ $p->judul }}</td>
                            <td><span class="badge bg-secondary-lt">{{ $p->kategori->kode }}</span></td>
                            <td>{{ $p->tahun_pencapaian }}</td>
                            <td><span class="badge bg-{{ $p->levelBadge() }}-lt">{{ $p->levelLabel() }}</span></td>
                            <td><span class="badge bg-{{ $p->statusBadge() }}-lt">{{ $p->statusLabel() }}</span></td>
                            <td>
                                @if ($p->is_publik && $p->mahasiswa->konsen_publik && in_array($p->status, ['diverifikasi', 'dipublikasikan']))
                                    <i class="bi bi-eye-fill text-success" title="Tampil di showcase"></i>
                                @else
                                    <i class="bi bi-eye-slash text-secondary" title="Tidak tampil di showcase"></i>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.verifikasi.show', $p) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-secondary py-5">
                                <i class="bi bi-inbox d-block fs-1 mb-2"></i>
                                Tidak ada entri yang cocok dengan filter.
                                <div class="mt-3">
                                    <a href="{{ route('admin.portofolio.index') }}" class="btn btn-sm btn-outline-secondary">Hapus Filter</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($portofolio->hasPages())
            <div class="card-footer">{{ $portofolio->links() }}</div>
        @endif
    </div>
@endsection
