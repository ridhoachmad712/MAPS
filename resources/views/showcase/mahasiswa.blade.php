@extends('layouts.publik')

@section('judul', 'Profil '.$mahasiswa->nama_lengkap)

@section('deskripsi', 'Profil capaian '.$mahasiswa->nama_lengkap.', mahasiswa Manajemen FEB UNM angkatan '.$mahasiswa->angkatan.' — '.$entri->count().' capaian terverifikasi: prestasi, sertifikasi, organisasi, dan lainnya.')

@section('og_type', 'profile')

@if ($mahasiswa->foto)
    @section('og_image', asset('storage/'.$mahasiswa->foto))
@endif

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $mahasiswa->nama_lengkap,
            'url' => route('showcase.mahasiswa', $mahasiswa),
            'affiliation' => [
                '@type' => 'CollegeOrUniversity',
                'name' => 'Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('konten')
    <div class="container-xl py-4">
        <ol class="breadcrumb mb-3" aria-label="breadcrumbs">
            <li class="breadcrumb-item"><a href="{{ route('showcase.mahasiswa.indeks') }}">Mahasiswa</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $mahasiswa->nama_lengkap }}</li>
        </ol>

        {{-- Kartu identitas --}}
        <div class="card mb-4">
            <div class="card-body d-flex flex-column flex-sm-row align-items-center gap-4">
                @if ($mahasiswa->foto)
                    <span class="avatar avatar-xl rounded-circle" style="background-image: url('{{ asset('storage/'.$mahasiswa->foto) }}')"></span>
                @else
                    <span class="avatar avatar-xl rounded-circle">{{ strtoupper(substr($mahasiswa->nama_lengkap, 0, 1)) }}</span>
                @endif
                <div class="text-center text-sm-start">
                    <h1 class="h2 mb-1">{{ $mahasiswa->nama_lengkap }}</h1>
                    <p class="text-secondary mb-2">
                        {{ $mahasiswa->nimSamar() }} · Angkatan {{ $mahasiswa->angkatan }} · {{ $mahasiswa->program_studi }}, FEB UNM
                    </p>
                    <span class="badge bg-blue-lt">
                        <i class="bi bi-patch-check me-1"></i>{{ $entri->count() }} capaian terverifikasi
                    </span>
                </div>
            </div>
        </div>

        {{-- Ringkasan capaian: COUNT otomatis per kategori --}}
        <div class="row row-cards row-cols-2 row-cols-sm-4 row-cols-xl-6 mb-4">
            @foreach ($perKategori as $k)
                <div class="col d-flex">
                    <div class="card w-100 text-center">
                        <div class="card-body py-3">
                            <div class="h1 mb-0">{{ $k->total }}</div>
                            <div class="text-secondary small">{{ $k->nama_kategori }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="h2 mb-3">Rekam Jejak Capaian Terverifikasi</h2>

        {{-- Dikelompokkan per tahun pencapaian, terbaru dulu --}}
        @foreach ($entri->groupBy('tahun_pencapaian') as $tahun => $entriTahun)
            <div class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="badge bg-primary text-primary-fg fs-5 px-3 py-2">{{ $tahun }}</span>
                    <span class="text-secondary small">{{ $entriTahun->count() }} capaian</span>
                    <span class="border-top flex-fill"></span>
                </div>
                <div class="row row-cards">
                    @foreach ($entriTahun as $p)
                        <div class="col-12 col-md-6 d-flex">
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                        <span class="badge bg-secondary-lt">{{ $p->kategori->nama_kategori }}</span>
                                        <span class="badge bg-{{ $p->levelBadge() }}-lt">{{ $p->levelLabel() }}</span>
                                    </div>
                                    <h3 class="card-title mb-1">{{ $p->judul }}</h3>
                                    <div class="text-secondary small">
                                        {{ $p->penyelenggara ?: 'Penyelenggara tidak dicantumkan' }}
                                    </div>
                                    @if ($p->peran_capaian)
                                        <div class="mt-1"><i class="bi bi-award text-secondary me-1"></i>{{ $p->peran_capaian }}</div>
                                    @endif
                                    @if ($p->deskripsi)
                                        <p class="text-secondary mt-2 mb-0">{{ $p->deskripsi }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
