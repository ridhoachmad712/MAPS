@extends('layouts.app')

@section('judul', $portofolio->judul)

@section('konten')
    <ol class="breadcrumb mb-3" aria-label="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('portofolio.index') }}">Portofolio Saya</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
    </ol>

    @include('partials.stepper-status')

    <div class="row g-3">
        <div class="col-12 col-lg-8 d-grid gap-3 align-content-start">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
                        <h1 class="h2 mb-0">{{ $portofolio->judul }}</h1>
                        <span class="badge bg-{{ $portofolio->statusBadge() }}-lt">{{ $portofolio->statusLabel() }}</span>
                    </div>
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <span class="badge bg-secondary-lt">{{ $portofolio->kategori->kode }} — {{ $portofolio->kategori->nama_kategori }}</span>
                        <span class="badge bg-{{ $portofolio->levelBadge() }}-lt">{{ $portofolio->levelLabel() }}</span>
                        <span class="badge bg-secondary-lt">{{ $portofolio->tahun_pencapaian }}</span>
                    </div>
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Penyelenggara</div>
                            <div class="datagrid-content">{{ $portofolio->penyelenggara ?: '—' }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Peran / Capaian</div>
                            <div class="datagrid-content">{{ $portofolio->peran_capaian ?: '—' }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Deskripsi</div>
                            <div class="datagrid-content">{{ $portofolio->deskripsi ?: '—' }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Tampil Publik</div>
                            <div class="datagrid-content">
                                @if ($portofolio->is_publik)
                                    <span class="text-success"><i class="bi bi-eye-fill me-1"></i>Disetujui tampil di showcase</span>
                                @else
                                    <span class="text-secondary"><i class="bi bi-eye-slash me-1"></i>Tidak ditampilkan di showcase</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-paperclip me-2"></i>Berkas Bukti ({{ $portofolio->bukti->count() }})</h3></div>
                <ul class="list-group list-group-flush">
                    @forelse ($portofolio->bukti as $b)
                        @include('partials.bukti-item', ['b' => $b, 'bolehHapus' => $portofolio->bisaDieditMahasiswa()])
                    @empty
                        <li class="list-group-item text-secondary">Belum ada berkas bukti.</li>
                    @endforelse
                </ul>
                @if ($portofolio->bisaDieditMahasiswa())
                    <div class="card-footer">
                        <form method="POST" action="{{ route('portofolio.bukti.store', $portofolio) }}" enctype="multipart/form-data">
                            @csrf
                            @include('portofolio._input-bukti', ['barisTautan' => 2, 'sufiks' => 'tambah'])
                            <button class="btn btn-sm btn-primary mt-3"><i class="bi bi-plus-lg me-1"></i>Tambah Bukti</button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-clock-history me-2"></i>Riwayat Verifikasi</h3></div>
                <ul class="list-group list-group-flush">
                    @forelse ($portofolio->verifikasi as $v)
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <span class="badge bg-{{ $v->hasilBadge() }}-lt">{{ $v->hasilLabel() }}</span>
                                <span class="text-secondary small">{{ $v->tanggal_verifikasi->format('d/m/Y H:i') }} · {{ $v->verifikator->username ?? '—' }}</span>
                            </div>
                            @if ($v->catatan)
                                <div class="mt-1"><i class="bi bi-chat-left-text me-1 text-secondary"></i>{{ $v->catatan }}</div>
                            @endif
                        </li>
                    @empty
                        <li class="list-group-item text-secondary">Belum pernah diverifikasi.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-sliders me-2"></i>Aksi</h3></div>
                <div class="card-body d-grid gap-2">
                    @if ($portofolio->bisaDiajukan())
                        <form method="POST" action="{{ route('portofolio.ajukan', $portofolio) }}">
                            @csrf
                            <button class="btn btn-primary w-100"
                                    onclick="return confirm('Ajukan entri ini untuk diverifikasi? Entri tidak dapat diubah selama proses verifikasi.')">
                                <i class="bi bi-send me-1"></i>Ajukan Verifikasi
                            </button>
                        </form>
                    @endif

                    @if ($portofolio->bisaDieditMahasiswa())
                        <a href="{{ route('portofolio.edit', $portofolio) }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-pencil me-1"></i>Ubah Entri
                        </a>
                        <form method="POST" action="{{ route('portofolio.destroy', $portofolio) }}"
                              onsubmit="return confirm('Hapus portofolio ini beserta seluruh buktinya?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger w-100"><i class="bi bi-trash me-1"></i>Hapus Entri</button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('portofolio.publik', $portofolio) }}">
                        @csrf
                        <button class="btn btn-outline-secondary w-100">
                            @if ($portofolio->is_publik)
                                <i class="bi bi-eye-slash me-1"></i>Tarik dari Halaman Publik
                            @else
                                <i class="bi bi-eye me-1"></i>Setujui Tampil Publik
                            @endif
                        </button>
                    </form>

                    @if ($portofolio->status === 'diajukan')
                        <div class="alert alert-warning mb-0 mt-2" role="alert">
                            <div class="d-flex gap-2">
                                <i class="bi bi-hourglass-split"></i>
                                <div>Entri sedang menunggu verifikasi dan tidak dapat diubah.</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
