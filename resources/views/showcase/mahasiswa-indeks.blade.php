@extends('layouts.publik')

@section('judul', 'Mahasiswa Berprestasi')

@section('deskripsi', 'Direktori mahasiswa berprestasi Prodi Manajemen FEB UNM beserta jumlah capaian terverifikasi masing-masing — cari berdasarkan nama atau angkatan.')

@section('konten')
    <div class="container-xl py-4">

        <div class="page-header mb-4">
            <div class="row align-items-end g-2">
                <div class="col">
                    <h1 class="page-title">Mahasiswa Berprestasi</h1>
                    <p class="text-secondary mb-0">
                        Mahasiswa dengan capaian terverifikasi yang disetujui tampil di halaman publik.
                    </p>
                </div>
                <div class="col-auto">
                    <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
                        <input type="text" name="q" class="form-control form-control-sm" style="width: 14rem;"
                               placeholder="Cari nama mahasiswa..." value="{{ request('q') }}">
                        <select name="angkatan" class="form-select form-select-sm" style="width: 10rem;" onchange="this.form.submit()">
                            <option value="">Semua angkatan</option>
                            @foreach ($daftarAngkatan as $a)
                                <option value="{{ $a }}" @selected(request('angkatan') == $a)>Angkatan {{ $a }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-primary btn-icon" aria-label="Cari"><i class="bi bi-search"></i></button>
                        @if (request()->hasAny(['q', 'angkatan']))
                            <a href="{{ route('showcase.mahasiswa.indeks') }}" class="btn btn-sm btn-outline-secondary">Atur Ulang</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="row row-cards row-cols-2 row-cols-sm-3 row-cols-lg-6">
            @forelse ($mahasiswa as $m)
                <div class="col d-flex">
                    <a href="{{ route('showcase.mahasiswa', $m) }}" class="card card-link w-100 text-center">
                        <div class="card-body">
                            @if ($m->foto)
                                <span class="avatar avatar-lg rounded-circle mx-auto" style="background-image: url('{{ asset('storage/'.$m->foto) }}')"></span>
                            @else
                                <span class="avatar avatar-lg rounded-circle mx-auto">{{ strtoupper(substr($m->nama_lengkap, 0, 1)) }}</span>
                            @endif
                            <div class="fw-semibold text-body text-truncate mt-3" title="{{ $m->nama_lengkap }}">{{ $m->nama_lengkap }}</div>
                            <div class="text-secondary small">{{ $m->nimSamar() }} · {{ $m->angkatan }}</div>
                            <span class="badge bg-blue-lt mt-2">{{ $m->total_publik }} capaian</span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center text-secondary py-5">
                            <i class="bi bi-people d-block fs-1 mb-2"></i>
                            Tidak ada mahasiswa yang cocok dengan pencarian Anda.
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($mahasiswa->hasPages())
            <div class="mt-4">{{ $mahasiswa->links() }}</div>
        @endif
    </div>
@endsection
