@extends('layouts.app')

@section('judul', 'Pemeriksaan: '.$portofolio->judul)

@section('konten')
    <ol class="breadcrumb mb-3" aria-label="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('admin.verifikasi.index') }}">Antrian Verifikasi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pemeriksaan Entri</li>
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
                            <div class="datagrid-title">Mahasiswa</div>
                            <div class="datagrid-content">
                                {{ $portofolio->mahasiswa->nama_lengkap }}
                                ({{ $portofolio->mahasiswa->nim }} · Angkatan {{ $portofolio->mahasiswa->angkatan }})
                            </div>
                        </div>
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
                            <div class="datagrid-title">Konsen Publik</div>
                            <div class="datagrid-content">
                                Entri: {!! $portofolio->is_publik ? '<span class="text-success">setuju tampil</span>' : '<span class="text-secondary">tidak</span>' !!} ·
                                Mahasiswa: {!! $portofolio->mahasiswa->konsen_publik ? '<span class="text-success">setuju tampil</span>' : '<span class="text-secondary">tidak</span>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title"><i class="bi bi-paperclip me-2"></i>Berkas Bukti ({{ $portofolio->bukti->count() }})</h3></div>
                <ul class="list-group list-group-flush">
                    @forelse ($portofolio->bukti as $b)
                        @include('partials.bukti-item', ['b' => $b, 'bolehHapus' => false])
                    @empty
                        <li class="list-group-item text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Tidak ada berkas bukti.</li>
                    @endforelse
                </ul>
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

        <div class="col-12 col-lg-4 d-grid gap-3 align-content-start">
            @if ($portofolio->status === 'diajukan')
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="bi bi-patch-check me-2"></i>Keputusan Verifikasi</h3></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.verifikasi.proses', $portofolio) }}" class="d-grid gap-3">
                            @csrf
                            <div>
                                <div class="form-label required">Hasil</div>
                                <div class="d-grid gap-2">
                                    <label class="form-check mb-0 text-success fw-semibold">
                                        <input class="form-check-input" type="radio" name="hasil" value="diverifikasi" required>
                                        <span class="form-check-label"><i class="bi bi-check-circle me-1"></i>Diverifikasi (sah)</span>
                                    </label>
                                    <label class="form-check mb-0 text-info fw-semibold">
                                        <input class="form-check-input" type="radio" name="hasil" value="revisi">
                                        <span class="form-check-label"><i class="bi bi-arrow-counterclockwise me-1"></i>Perlu Revisi</span>
                                    </label>
                                    <label class="form-check mb-0 text-danger fw-semibold">
                                        <input class="form-check-input" type="radio" name="hasil" value="ditolak">
                                        <span class="form-check-label"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Catatan <span class="form-label-description">(wajib jika revisi/tolak)</span></label>
                                <textarea name="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror"
                                          placeholder="Alasan atau arahan untuk mahasiswa...">{{ old('catatan') }}</textarea>
                                @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button class="btn btn-primary w-100"><i class="bi bi-send-check me-1"></i>Simpan Keputusan</button>
                        </form>
                    </div>
                </div>
            @endif

            @if (auth()->user()->isAdmin())
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="bi bi-megaphone me-2"></i>Publikasi Showcase</h3></div>
                    <div class="card-body d-grid gap-2">
                        @if ($portofolio->status === 'diverifikasi')
                            <form method="POST" action="{{ route('admin.portofolio.publikasikan', $portofolio) }}">
                                @csrf
                                <button class="btn btn-primary w-100"><i class="bi bi-megaphone me-1"></i>Publikasikan ke Showcase</button>
                            </form>
                            <p class="text-secondary small mb-0">
                                Entri tampil di halaman publik hanya jika mahasiswa juga memberi persetujuan (konsen profil &amp; entri).
                            </p>
                        @elseif ($portofolio->status === 'dipublikasikan')
                            <form method="POST" action="{{ route('admin.portofolio.batalkan', $portofolio) }}">
                                @csrf
                                <button class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise me-1"></i>Batalkan Publikasi</button>
                            </form>
                        @else
                            <p class="text-secondary mb-0">Entri dapat dipublikasikan setelah berstatus <strong>Diverifikasi</strong>.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
