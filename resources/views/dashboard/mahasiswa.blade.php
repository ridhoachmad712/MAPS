@extends('layouts.app')

@section('judul', 'Dashboard Mahasiswa')

@section('konten')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-extrabold text-navy-700">Halo, {{ $mahasiswa->nama_lengkap }} 👋</h1>
            <p class="mt-0.5 text-sm text-slate-500">{{ $mahasiswa->nim }} · Angkatan {{ $mahasiswa->angkatan }} · {{ $mahasiswa->program_studi }}</p>
        </div>
        <a href="{{ route('portofolio.create') }}" class="btn btn-maps">
            <i class="bi bi-plus-circle"></i>Tambah Portofolio
        </a>
    </div>

    @unless ($mahasiswa->konsen_publik)
        <div class="alert alert-warning">
            <i class="bi bi-eye-slash mt-0.5"></i>
            <span>
                Anda belum menyetujui penampilan capaian di halaman publik.
                Capaian terverifikasi Anda tidak akan muncul di showcase sampai persetujuan diaktifkan di
                <a href="{{ route('profil.edit') }}" class="font-semibold underline">halaman profil</a>.
            </span>
        </div>
    @endunless

    {{-- Profil capaian otomatis (COUNT per kategori, entri terverifikasi) --}}
    <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-7">
        @foreach ($perKategori as $k)
            <div class="card">
                <div class="px-4 py-3">
                    <div class="truncate text-xs text-slate-500" title="{{ $k->nama_kategori }}">{{ $k->nama_kategori }}</div>
                    <div class="text-2xl font-extrabold text-navy-700">{{ $k->total }}</div>
                    <div class="text-xs text-green-600">terverifikasi</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="card">
            <div class="card-header"><i class="bi bi-clipboard-data"></i>Status Pengajuan</div>
            <ul class="divide-y divide-slate-100">
                @foreach (\App\Models\Portofolio::STATUS_LABEL as $status => $label)
                    <li class="flex items-center justify-between px-5 py-3">
                        <span class="badge badge-{{ \App\Models\Portofolio::STATUS_BADGE[$status] }}">{{ $label }}</span>
                        <strong>{{ $statusCounts[$status] ?? 0 }}</strong>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="card lg:col-span-2">
            <div class="card-header"><i class="bi bi-clock-history"></i>Aktivitas Terbaru</div>
            @forelse ($terbaru as $p)
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-3 last:border-0">
                    <div class="min-w-0">
                        <a href="{{ route('portofolio.show', $p) }}" class="font-semibold text-navy-700 hover:underline">{{ $p->judul }}</a>
                        <div class="text-xs text-slate-500">
                            {{ $p->kategori->nama_kategori }} · {{ $p->tahun_pencapaian }} · {{ $p->levelLabel() }}
                        </div>
                        @if ($p->verifikasi->isNotEmpty() && $p->verifikasi->first()->catatan && in_array($p->status, ['revisi', 'ditolak']))
                            <div class="mt-0.5 text-xs text-red-600"><i class="bi bi-chat-left-text"></i> {{ $p->verifikasi->first()->catatan }}</div>
                        @endif
                    </div>
                    <span class="badge badge-{{ $p->statusBadge() }}">{{ $p->statusLabel() }}</span>
                </div>
            @empty
                <div class="px-5 py-12 text-center">
                    <i class="bi bi-journal-plus mb-2 block text-4xl text-slate-300"></i>
                    <p class="font-semibold text-slate-600">Belum ada portofolio</p>
                    <p class="mt-1 text-sm text-slate-400">Mulai arsipkan prestasi, sertifikasi, organisasi, dan capaian lainnya.</p>
                    <a href="{{ route('portofolio.create') }}" class="btn btn-sm btn-maps mt-4">
                        <i class="bi bi-plus-circle"></i>Tambah Capaian Pertama
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
