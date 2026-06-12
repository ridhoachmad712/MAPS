@extends('layouts.publik')

@section('judul', 'Mahasiswa Berprestasi')

@section('deskripsi', 'Direktori mahasiswa berprestasi Prodi Manajemen FEB UNM beserta jumlah capaian terverifikasi masing-masing — cari berdasarkan nama atau angkatan.')

@section('konten')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-navy-700">Mahasiswa Berprestasi</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Mahasiswa dengan capaian terverifikasi yang disetujui tampil di halaman publik.
                </p>
            </div>
            <form method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="q" class="form-control form-control-sm w-56" placeholder="Cari nama mahasiswa..."
                       value="{{ request('q') }}">
                <select name="angkatan" class="form-select form-select-sm w-36" onchange="this.form.submit()">
                    <option value="">Semua angkatan</option>
                    @foreach ($daftarAngkatan as $a)
                        <option value="{{ $a }}" @selected(request('angkatan') == $a)>Angkatan {{ $a }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-maps" aria-label="Cari"><i class="bi bi-search"></i></button>
                @if (request()->hasAny(['q', 'angkatan']))
                    <a href="{{ route('showcase.mahasiswa.indeks') }}" class="btn btn-sm btn-outline">Atur Ulang</a>
                @endif
            </form>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @forelse ($mahasiswa as $m)
                <a href="{{ route('showcase.mahasiswa', $m) }}" class="card card-hover p-5 text-center">
                    @if ($m->foto)
                        <img src="{{ asset('storage/'.$m->foto) }}" alt="Foto {{ $m->nama_lengkap }}"
                             class="mx-auto h-16 w-16 rounded-full object-cover">
                    @else
                        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-navy-50 text-xl font-bold text-navy-600">
                            {{ strtoupper(substr($m->nama_lengkap, 0, 1)) }}
                        </span>
                    @endif
                    <div class="mt-3 truncate text-sm font-semibold text-gray-900" title="{{ $m->nama_lengkap }}">{{ $m->nama_lengkap }}</div>
                    <div class="text-xs text-gray-500">{{ $m->nimSamar() }} · {{ $m->angkatan }}</div>
                    <span class="badge badge-primary mt-2.5">{{ $m->total_publik }} capaian</span>
                </a>
            @empty
                <div class="col-span-full">
                    <div class="card py-14 text-center text-slate-400">
                        <i class="bi bi-people mb-2 block text-4xl"></i>
                        Tidak ada mahasiswa yang cocok dengan pencarian Anda.
                    </div>
                </div>
            @endforelse
        </div>

        @if ($mahasiswa->hasPages())
            <div class="mt-6">{{ $mahasiswa->links() }}</div>
        @endif
    </div>
@endsection
