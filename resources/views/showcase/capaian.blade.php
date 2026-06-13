@extends('layouts.publik')

@section('judul', 'Jelajah Capaian')

@section('deskripsi', 'Jelajahi seluruh capaian terverifikasi mahasiswa Prodi Manajemen FEB UNM — saring berdasarkan kategori, level, tahun pencapaian, dan angkatan.')

@php
    $filterAktif = collect([
        'q' => request('q') ? 'Cari: "'.request('q').'"' : null,
        'kategori' => request('kategori')
            ? 'Kategori: '.optional($kategori->firstWhere('kategori_id', (int) request('kategori')))->nama_kategori
            : null,
        'level' => request('level') ? 'Level: '.(\App\Models\Portofolio::LEVEL_LABEL[request('level')] ?? request('level')) : null,
        'tahun' => request('tahun') ? 'Tahun: '.request('tahun') : null,
        'angkatan' => request('angkatan') ? 'Angkatan: '.request('angkatan') : null,
    ])->filter();

    $urut = request('urut');
@endphp

@section('konten')
    <div class="container-xl py-4">

        <div class="page-header mb-4">
            <div class="row align-items-end g-2">
                <div class="col">
                    <h1 class="page-title">Jelajah Capaian</h1>
                    <p class="text-secondary mb-0">
                        Seluruh capaian terverifikasi yang disetujui tampil publik — saring sesuai kebutuhan.
                    </p>
                </div>
                <div class="col-auto">
                    <p class="text-secondary small mb-0">
                        <i class="bi bi-patch-check me-1"></i>
                        Seluruh angka dihitung otomatis dari entri yang telah diverifikasi prodi.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-3">

            {{-- Panel filter kiri (sticky, dapat dilipat di layar sempit) --}}
            <aside class="col-12 col-lg-3">
                <form method="GET" id="formFilter" class="card sticky-lg-top" style="top: 4.5rem;">
                    <button type="button" class="card-header d-flex justify-content-between align-items-center w-100 border-0 bg-transparent d-lg-none"
                            data-bs-toggle="collapse" data-bs-target="#panelFilter" aria-expanded="false" aria-controls="panelFilter">
                        <span class="card-title mb-0"><i class="bi bi-funnel me-1"></i>Filter</span>
                        <span class="d-flex align-items-center gap-2">
                            @if ($filterAktif->isNotEmpty())
                                <span class="badge bg-blue-lt">{{ $filterAktif->count() }}</span>
                            @endif
                            <i class="bi bi-chevron-down text-secondary"></i>
                        </span>
                    </button>
                    <div class="card-header d-none d-lg-flex">
                        <h3 class="card-title"><i class="bi bi-funnel me-1"></i>Filter</h3>
                        @if ($filterAktif->isNotEmpty())
                            <span class="badge bg-blue-lt ms-auto">{{ $filterAktif->count() }}</span>
                        @endif
                    </div>

                    <div class="collapse d-lg-block" id="panelFilter">
                        <div class="card-body d-grid gap-3">
                            <div>
                                <label class="form-label">Pencarian</label>
                                <div class="input-group">
                                    <input type="text" name="q" class="form-control" value="{{ request('q') }}"
                                           placeholder="Judul, penyelenggara, nama...">
                                    <button class="btn btn-primary btn-icon" aria-label="Cari"><i class="bi bi-search"></i></button>
                                </div>
                            </div>

                            <div>
                                <div class="form-label">Kategori</div>
                                <div class="d-grid gap-1">
                                    <label class="form-check mb-0 d-flex justify-content-between">
                                        <span>
                                            <input type="radio" name="kategori" value="" class="form-check-input" @checked(!request()->filled('kategori'))>
                                            <span class="form-check-label d-inline">Semua kategori</span>
                                        </span>
                                    </label>
                                    @foreach ($kategori as $k)
                                        <label class="form-check mb-0 d-flex justify-content-between">
                                            <span>
                                                <input type="radio" name="kategori" value="{{ $k->kategori_id }}" class="form-check-input"
                                                       @checked(request('kategori') == $k->kategori_id)>
                                                <span class="form-check-label d-inline">{{ $k->nama_kategori }}</span>
                                            </span>
                                            <span class="badge bg-secondary-lt">{{ $k->total_publik }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <div class="form-label">Level</div>
                                <div class="d-grid gap-1">
                                    <label class="form-check mb-0">
                                        <input type="radio" name="level" value="" class="form-check-input" @checked(!request()->filled('level'))>
                                        <span class="form-check-label">Semua level</span>
                                    </label>
                                    @foreach (\App\Models\Portofolio::LEVEL_LABEL as $nilai => $label)
                                        <label class="form-check mb-0">
                                            <input type="radio" name="level" value="{{ $nilai }}" class="form-check-input" @checked(request('level') === $nilai)>
                                            <span class="form-check-label">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label">Tahun</label>
                                    <select name="tahun" class="form-select form-select-sm">
                                        <option value="">Semua</option>
                                        @foreach ($daftarTahun as $t)
                                            <option value="{{ $t }}" @selected(request('tahun') == $t)>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Angkatan</label>
                                    <select name="angkatan" class="form-select form-select-sm">
                                        <option value="">Semua</option>
                                        @foreach ($daftarAngkatan as $a)
                                            <option value="{{ $a }}" @selected(request('angkatan') == $a)>{{ $a }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </aside>

            {{-- Konten utama --}}
            <div class="col-12 col-lg-9 d-grid gap-3 align-content-start">

                {{-- Chip filter aktif --}}
                @if ($filterAktif->isNotEmpty())
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="text-secondary text-uppercase small fw-semibold">Filter aktif:</span>
                        @foreach ($filterAktif as $kunci => $label)
                            <a href="{{ route('showcase.capaian', collect(request()->except([$kunci, 'page']))->all()) }}"
                               class="badge bg-blue-lt" title="Lepas filter ini">
                                {{ $label }} <i class="bi bi-x ms-1"></i>
                            </a>
                        @endforeach
                        <a href="{{ route('showcase.capaian') }}" class="small fw-semibold link-secondary">
                            Hapus semua
                        </a>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-list-check me-1"></i>Daftar capaian terverifikasi</h3>
                        <span class="text-secondary small ms-auto">{{ $entri->total() }} entri</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter table-hover card-table">
                            <thead>
                                <tr>
                                    <th>Capaian</th>
                                    <th>Mahasiswa</th>
                                    <th class="d-none d-md-table-cell">Kategori</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['urut' => $urut === 'level_turun' ? 'level_naik' : 'level_turun', 'page' => null]) }}"
                                           class="d-inline-flex align-items-center gap-1 text-reset">
                                            Level
                                            @if ($urut === 'level_turun') <i class="bi bi-arrow-down"></i>
                                            @elseif ($urut === 'level_naik') <i class="bi bi-arrow-up"></i>
                                            @else <i class="bi bi-arrow-down-up opacity-50"></i> @endif
                                        </a>
                                    </th>
                                    <th class="text-end">
                                        <a href="{{ request()->fullUrlWithQuery(['urut' => $urut === 'tahun_turun' ? 'tahun_naik' : 'tahun_turun', 'page' => null]) }}"
                                           class="d-inline-flex align-items-center gap-1 text-reset">
                                            Tahun
                                            @if ($urut === 'tahun_turun') <i class="bi bi-arrow-down"></i>
                                            @elseif ($urut === 'tahun_naik') <i class="bi bi-arrow-up"></i>
                                            @else <i class="bi bi-arrow-down-up opacity-50"></i> @endif
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($entri as $p)
                                    <tr role="link" style="cursor: pointer;" onclick="window.location='{{ route('showcase.mahasiswa', $p->mahasiswa) }}'">
                                        <td>
                                            <div class="fw-semibold">{{ $p->judul }}</div>
                                            <div class="text-secondary small">
                                                {{ $p->penyelenggara ?: 'Penyelenggara tidak dicantumkan' }}
                                                @if ($p->peran_capaian) · {{ $p->peran_capaian }} @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $p->mahasiswa->nama_lengkap }}</span>
                                            <div class="text-secondary small">{{ $p->mahasiswa->nimSamar() }} · {{ $p->mahasiswa->angkatan }}</div>
                                        </td>
                                        <td class="d-none d-md-table-cell"><span class="badge bg-secondary-lt">{{ $p->kategori->kode }}</span></td>
                                        <td><span class="badge bg-{{ $p->levelBadge() }}-lt">{{ $p->levelLabel() }}</span></td>
                                        <td class="text-end fw-medium">{{ $p->tahun_pencapaian }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-5">
                                            <i class="bi bi-search d-block fs-1 mb-2"></i>
                                            Tidak ada capaian yang cocok dengan filter Anda.
                                            @if ($filterAktif->isNotEmpty())
                                                <div class="mt-3">
                                                    <a href="{{ route('showcase.capaian') }}" class="btn btn-sm btn-outline-secondary">Hapus semua filter</a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($entri->hasPages())
                        <div class="card-footer">{{ $entri->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    // Filter langsung diterapkan saat pilihan berubah
    const formFilter = document.getElementById('formFilter');
    formFilter.addEventListener('change', (e) => {
        if (e.target.matches('input[type="radio"], select')) formFilter.submit();
    });
</script>
@endpush
