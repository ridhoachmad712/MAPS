@extends('layouts.app')

@section('judul', 'Portofolio Saya')

@section('konten')
    <div class="page-header mb-4">
        <div class="row align-items-center g-2">
            <div class="col">
                <h1 class="page-title">Portofolio Saya</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('portofolio.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah Portofolio</a>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <form method="GET" class="card-body row g-3 align-items-end">
            <div class="col-12 col-sm-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua status</option>
                    @foreach (\App\Models\Portofolio::STATUS_LABEL as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(request('status') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-4">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua kategori</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->kategori_id }}" @selected(request('kategori') == $k->kategori_id)>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i>Saring</button>
                <a href="{{ route('portofolio.index') }}" class="btn btn-sm btn-outline-secondary">Atur Ulang</a>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Level</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Publik</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($portofolio as $p)
                        <tr>
                            <td>
                                <a href="{{ route('portofolio.show', $p) }}" class="fw-semibold">{{ $p->judul }}</a>
                            </td>
                            <td><span class="badge bg-secondary-lt">{{ $p->kategori->kode }}</span></td>
                            <td>{{ $p->tahun_pencapaian }}</td>
                            <td><span class="badge bg-{{ $p->levelBadge() }}-lt">{{ $p->levelLabel() }}</span></td>
                            <td>{{ $p->bukti->count() }} berkas</td>
                            <td><span class="badge bg-{{ $p->statusBadge() }}-lt">{{ $p->statusLabel() }}</span></td>
                            <td>
                                @if ($p->is_publik)
                                    <i class="bi bi-eye-fill text-success" title="Disetujui tampil publik"></i>
                                @else
                                    <i class="bi bi-eye-slash text-secondary" title="Tidak tampil publik"></i>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('portofolio.show', $p) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($p->bisaDieditMahasiswa())
                                        <a href="{{ route('portofolio.edit', $p) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Ubah">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-journal-plus d-block fs-1 text-secondary mb-2"></i>
                                <p class="fw-semibold mb-1">Belum ada portofolio yang cocok</p>
                                @if (request()->hasAny(['status', 'kategori']))
                                    <p class="text-secondary mb-3">Coba longgarkan filter, atau tambah capaian baru.</p>
                                    <a href="{{ route('portofolio.index') }}" class="btn btn-sm btn-outline-secondary">Hapus Filter</a>
                                @else
                                    <p class="text-secondary mb-3">Mulai arsipkan prestasi, sertifikasi, organisasi, dan capaian lainnya.</p>
                                @endif
                                <a href="{{ route('portofolio.create') }}" class="btn btn-sm btn-primary ms-1">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Portofolio
                                </a>
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
