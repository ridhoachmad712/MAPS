@extends('layouts.publik')

@section('judul', 'Profil '.$mahasiswa->nama_lengkap)

@section('konten')
    <div class="mx-auto max-w-7xl px-4 py-6">
        <nav class="mb-4 text-sm text-slate-500">
            <a href="{{ route('showcase.index') }}" class="hover:text-navy-700 hover:underline">Data Capaian</a>
            <span class="mx-1.5">/</span>
            <span class="text-slate-700">Profil Mahasiswa</span>
        </nav>

        {{-- Kartu identitas --}}
        <div class="card mb-5">
            <div class="flex flex-col items-center gap-5 p-6 sm:flex-row">
                @if ($mahasiswa->foto)
                    <img src="{{ asset('storage/'.$mahasiswa->foto) }}" alt="Foto {{ $mahasiswa->nama_lengkap }}" class="foto-avatar-besar">
                @else
                    <span class="avatar-inisial-besar">{{ strtoupper(substr($mahasiswa->nama_lengkap, 0, 1)) }}</span>
                @endif
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl font-extrabold tracking-tight text-navy-700">{{ $mahasiswa->nama_lengkap }}</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $mahasiswa->nimSamar() }} · Angkatan {{ $mahasiswa->angkatan }} · {{ $mahasiswa->program_studi }}, FEB UNM
                    </p>
                    <p class="mt-2">
                        <span class="badge badge-primary">
                            <i class="bi bi-patch-check"></i>{{ $entri->count() }} capaian terverifikasi
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Ringkasan capaian: COUNT otomatis per kategori --}}
        <div class="mb-8 grid grid-cols-2 gap-3 sm:grid-cols-4 xl:grid-cols-6">
            @foreach ($perKategori as $k)
                <div class="card text-center">
                    <div class="px-3 py-4">
                        <div class="text-2xl font-extrabold text-navy-700">{{ $k->total }}</div>
                        <div class="text-xs text-slate-500">{{ $k->nama_kategori }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="mb-4 text-lg font-extrabold text-navy-700">Daftar Capaian Terverifikasi</h2>
        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($entri as $p)
                <div class="card card-hover">
                    <div class="card-body">
                        <div class="mb-2.5 flex items-start justify-between gap-2">
                            <span class="badge badge-soft">{{ $p->kategori->nama_kategori }}</span>
                            <span class="badge badge-level-{{ $p->level }}">{{ $p->levelLabel() }}</span>
                        </div>
                        <h3 class="mb-1 font-bold text-navy-700">{{ $p->judul }}</h3>
                        <div class="text-xs text-slate-500">
                            {{ $p->penyelenggara ?: 'Penyelenggara tidak dicantumkan' }} · {{ $p->tahun_pencapaian }}
                        </div>
                        @if ($p->peran_capaian)
                            <div class="mt-1.5 text-sm"><i class="bi bi-award text-slate-400"></i> {{ $p->peran_capaian }}</div>
                        @endif
                        @if ($p->deskripsi)
                            <p class="mt-2 text-sm text-slate-500">{{ $p->deskripsi }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
