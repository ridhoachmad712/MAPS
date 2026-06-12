@extends('layouts.app')

@section('judul', $portofolio->judul)

@section('konten')
    <nav class="mb-4 text-sm text-slate-500">
        <a href="{{ route('portofolio.index') }}" class="hover:text-navy-700 hover:underline">Portofolio Saya</a>
        <span class="mx-1.5">/</span>
        <span class="text-slate-700">Detail</span>
    </nav>

    @include('partials.stepper-status')

    <div class="grid gap-4 lg:grid-cols-12">
        <div class="space-y-4 lg:col-span-8">
            <div class="card">
                <div class="card-body p-6">
                    <div class="mb-3 flex flex-wrap items-start justify-between gap-3">
                        <h1 class="text-xl font-extrabold text-navy-700">{{ $portofolio->judul }}</h1>
                        <span class="badge badge-{{ $portofolio->statusBadge() }}">{{ $portofolio->statusLabel() }}</span>
                    </div>
                    <div class="mb-4 flex flex-wrap gap-1.5">
                        <span class="badge badge-soft">{{ $portofolio->kategori->kode }} — {{ $portofolio->kategori->nama_kategori }}</span>
                        <span class="badge badge-level-{{ $portofolio->level }}">{{ $portofolio->levelLabel() }}</span>
                        <span class="badge badge-soft">{{ $portofolio->tahun_pencapaian }}</span>
                    </div>
                    <dl class="grid gap-x-6 gap-y-3 text-sm sm:grid-cols-[10rem_1fr]">
                        <dt class="font-semibold text-slate-500">Penyelenggara</dt>
                        <dd>{{ $portofolio->penyelenggara ?: '—' }}</dd>
                        <dt class="font-semibold text-slate-500">Peran / Capaian</dt>
                        <dd>{{ $portofolio->peran_capaian ?: '—' }}</dd>
                        <dt class="font-semibold text-slate-500">Deskripsi</dt>
                        <dd>{{ $portofolio->deskripsi ?: '—' }}</dd>
                        <dt class="font-semibold text-slate-500">Tampil Publik</dt>
                        <dd>
                            @if ($portofolio->is_publik)
                                <span class="text-green-600"><i class="bi bi-eye-fill"></i> Disetujui tampil di showcase</span>
                            @else
                                <span class="text-slate-400"><i class="bi bi-eye-slash"></i> Tidak ditampilkan di showcase</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><i class="bi bi-paperclip"></i>Berkas Bukti ({{ $portofolio->bukti->count() }})</div>
                <ul class="divide-y divide-slate-100">
                    @forelse ($portofolio->bukti as $b)
                        @include('partials.bukti-item', ['b' => $b, 'bolehHapus' => $portofolio->bisaDieditMahasiswa()])
                    @empty
                        <li class="px-5 py-4 text-sm text-slate-400">Belum ada berkas bukti.</li>
                    @endforelse
                </ul>
                @if ($portofolio->bisaDieditMahasiswa())
                    <div class="border-t border-slate-100 px-5 py-4">
                        <form method="POST" action="{{ route('portofolio.bukti.store', $portofolio) }}" enctype="multipart/form-data" class="flex gap-2">
                            @csrf
                            <input type="file" name="bukti[]" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" multiple required>
                            <button class="btn btn-sm btn-maps"><i class="bi bi-upload"></i>Unggah</button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="card">
                <div class="card-header"><i class="bi bi-clock-history"></i>Riwayat Verifikasi</div>
                <ul class="divide-y divide-slate-100">
                    @forelse ($portofolio->verifikasi as $v)
                        <li class="px-5 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <span class="badge badge-{{ $v->hasilBadge() }}">{{ $v->hasilLabel() }}</span>
                                <span class="text-xs text-slate-400">{{ $v->tanggal_verifikasi->format('d/m/Y H:i') }} · {{ $v->verifikator->username ?? '—' }}</span>
                            </div>
                            @if ($v->catatan)
                                <div class="mt-1.5 text-sm text-slate-600"><i class="bi bi-chat-left-text"></i> {{ $v->catatan }}</div>
                            @endif
                        </li>
                    @empty
                        <li class="px-5 py-4 text-sm text-slate-400">Belum pernah diverifikasi.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="card">
                <div class="card-header"><i class="bi bi-sliders"></i>Aksi</div>
                <div class="card-body grid gap-2">
                    @if ($portofolio->bisaDiajukan())
                        <form method="POST" action="{{ route('portofolio.ajukan', $portofolio) }}">
                            @csrf
                            <button class="btn btn-maps w-full"
                                    onclick="return confirm('Ajukan entri ini untuk diverifikasi? Entri tidak dapat diubah selama proses verifikasi.')">
                                <i class="bi bi-send"></i>Ajukan Verifikasi
                            </button>
                        </form>
                    @endif

                    @if ($portofolio->bisaDieditMahasiswa())
                        <a href="{{ route('portofolio.edit', $portofolio) }}" class="btn btn-outline-primary w-full">
                            <i class="bi bi-pencil"></i>Ubah Entri
                        </a>
                        <form method="POST" action="{{ route('portofolio.destroy', $portofolio) }}"
                              onsubmit="return confirm('Hapus portofolio ini beserta seluruh buktinya?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger w-full"><i class="bi bi-trash"></i>Hapus Entri</button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('portofolio.publik', $portofolio) }}">
                        @csrf
                        <button class="btn btn-outline w-full">
                            @if ($portofolio->is_publik)
                                <i class="bi bi-eye-slash"></i>Tarik dari Halaman Publik
                            @else
                                <i class="bi bi-eye"></i>Setujui Tampil Publik
                            @endif
                        </button>
                    </form>

                    @if ($portofolio->status === 'diajukan')
                        <div class="alert alert-warning mb-0 mt-2 text-xs">
                            <i class="bi bi-hourglass-split mt-0.5"></i>
                            <span>Entri sedang menunggu verifikasi dan tidak dapat diubah.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
