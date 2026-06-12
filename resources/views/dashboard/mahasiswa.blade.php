@extends('layouts.app')

@section('judul', 'Dashboard Mahasiswa')

@section('konten')
    <div class="page-header mb-4">
        <div class="row align-items-center g-2">
            <div class="col">
                <h1 class="page-title">Halo, {{ $mahasiswa->nama_lengkap }} 👋</h1>
                <p class="text-secondary mb-0">{{ $mahasiswa->nim }} · Angkatan {{ $mahasiswa->angkatan }} · {{ $mahasiswa->program_studi }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('portofolio.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Portofolio
                </a>
            </div>
        </div>
    </div>

    @unless ($mahasiswa->konsen_publik)
        <div class="alert alert-warning" role="alert">
            <div class="d-flex gap-2">
                <i class="bi bi-eye-slash"></i>
                <div>
                    Anda belum menyetujui penampilan capaian di halaman publik.
                    Capaian terverifikasi Anda tidak akan muncul di showcase sampai persetujuan diaktifkan di
                    <a href="{{ route('profil.edit') }}" class="fw-semibold">halaman profil</a>.
                </div>
            </div>
        </div>
    @endunless

    {{-- Profil capaian otomatis (COUNT per kategori, entri terverifikasi) --}}
    <div class="row row-cards row-cols-2 row-cols-sm-3 row-cols-xl-7 mb-3">
        @foreach ($perKategori as $k)
            <div class="col d-flex">
                <div class="card w-100">
                    <div class="card-body py-3">
                        <div class="text-secondary small text-truncate" title="{{ $k->nama_kategori }}">{{ $k->nama_kategori }}</div>
                        <div class="h1 mb-0">{{ $k->total }}</div>
                        <div class="text-success small">terverifikasi</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row row-cards">
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-clipboard-data me-2"></i>Status Pengajuan</h3></div>
                <ul class="list-group list-group-flush">
                    @foreach (\App\Models\Portofolio::STATUS_LABEL as $status => $label)
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="badge bg-{{ \App\Models\Portofolio::STATUS_BADGE[$status] }}-lt">{{ $label }}</span>
                            <strong>{{ $statusCounts[$status] ?? 0 }}</strong>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h3></div>
                <ul class="list-group list-group-flush">
                    @forelse ($terbaru as $p)
                        <li class="list-group-item d-flex align-items-center justify-content-between gap-3">
                            <div class="min-width-0">
                                <a href="{{ route('portofolio.show', $p) }}" class="fw-semibold">{{ $p->judul }}</a>
                                <div class="text-secondary small">
                                    {{ $p->kategori->nama_kategori }} · {{ $p->tahun_pencapaian }} · {{ $p->levelLabel() }}
                                </div>
                                @if ($p->verifikasi->isNotEmpty() && $p->verifikasi->first()->catatan && in_array($p->status, ['revisi', 'ditolak']))
                                    <div class="text-danger small mt-1"><i class="bi bi-chat-left-text me-1"></i>{{ $p->verifikasi->first()->catatan }}</div>
                                @endif
                            </div>
                            <span class="badge bg-{{ $p->statusBadge() }}-lt flex-shrink-0">{{ $p->statusLabel() }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center py-5">
                            <i class="bi bi-journal-plus d-block fs-1 text-secondary mb-2"></i>
                            <p class="fw-semibold mb-1">Belum ada portofolio</p>
                            <p class="text-secondary mb-3">Mulai arsipkan prestasi, sertifikasi, organisasi, dan capaian lainnya.</p>
                            <a href="{{ route('portofolio.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Tambah Capaian Pertama
                            </a>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
