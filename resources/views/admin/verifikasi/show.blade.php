@extends('layouts.app')

@section('judul', 'Pemeriksaan: '.$portofolio->judul)

@section('konten')
    <nav class="mb-4 text-sm text-slate-500">
        <a href="{{ route('admin.verifikasi.index') }}" class="hover:text-navy-700 hover:underline">Antrian Verifikasi</a>
        <span class="mx-1.5">/</span>
        <span class="text-slate-700">Pemeriksaan Entri</span>
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
                        <dt class="font-semibold text-slate-500">Mahasiswa</dt>
                        <dd>
                            {{ $portofolio->mahasiswa->nama_lengkap }}
                            ({{ $portofolio->mahasiswa->nim }} · Angkatan {{ $portofolio->mahasiswa->angkatan }})
                        </dd>
                        <dt class="font-semibold text-slate-500">Penyelenggara</dt>
                        <dd>{{ $portofolio->penyelenggara ?: '—' }}</dd>
                        <dt class="font-semibold text-slate-500">Peran / Capaian</dt>
                        <dd>{{ $portofolio->peran_capaian ?: '—' }}</dd>
                        <dt class="font-semibold text-slate-500">Deskripsi</dt>
                        <dd>{{ $portofolio->deskripsi ?: '—' }}</dd>
                        <dt class="font-semibold text-slate-500">Konsen Publik</dt>
                        <dd>
                            Entri: {!! $portofolio->is_publik ? '<span class="text-green-600">setuju tampil</span>' : '<span class="text-slate-400">tidak</span>' !!} ·
                            Mahasiswa: {!! $portofolio->mahasiswa->konsen_publik ? '<span class="text-green-600">setuju tampil</span>' : '<span class="text-slate-400">tidak</span>' !!}
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><i class="bi bi-paperclip"></i>Berkas Bukti ({{ $portofolio->bukti->count() }})</div>
                <ul class="divide-y divide-slate-100">
                    @forelse ($portofolio->bukti as $b)
                        @include('partials.bukti-item', ['b' => $b, 'bolehHapus' => false])
                    @empty
                        <li class="px-5 py-4 text-sm text-red-600"><i class="bi bi-exclamation-triangle"></i> Tidak ada berkas bukti.</li>
                    @endforelse
                </ul>
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

        <div class="space-y-4 lg:col-span-4">
            @if ($portofolio->status === 'diajukan')
                <div class="card">
                    <div class="card-header"><i class="bi bi-patch-check"></i>Keputusan Verifikasi</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.verifikasi.proses', $portofolio) }}" class="space-y-4">
                            @csrf
                            <div>
                                <span class="form-label">Hasil <span class="text-red-600">*</span></span>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2.5 text-sm font-semibold text-green-700">
                                        <input class="form-check-input" type="radio" name="hasil" value="diverifikasi" required>
                                        <i class="bi bi-check-circle"></i>Diverifikasi (sah)
                                    </label>
                                    <label class="flex items-center gap-2.5 text-sm font-semibold text-cyan-700">
                                        <input class="form-check-input" type="radio" name="hasil" value="revisi">
                                        <i class="bi bi-arrow-counterclockwise"></i>Perlu Revisi
                                    </label>
                                    <label class="flex items-center gap-2.5 text-sm font-semibold text-red-700">
                                        <input class="form-check-input" type="radio" name="hasil" value="ditolak">
                                        <i class="bi bi-x-circle"></i>Ditolak
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Catatan <span class="font-normal text-slate-400">(wajib jika revisi/tolak)</span></label>
                                <textarea name="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror"
                                          placeholder="Alasan atau arahan untuk mahasiswa...">{{ old('catatan') }}</textarea>
                                @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button class="btn btn-maps w-full"><i class="bi bi-send-check"></i>Simpan Keputusan</button>
                        </form>
                    </div>
                </div>
            @endif

            @if (auth()->user()->isAdmin())
                <div class="card">
                    <div class="card-header"><i class="bi bi-megaphone"></i>Publikasi Showcase</div>
                    <div class="card-body grid gap-2.5">
                        @if ($portofolio->status === 'diverifikasi')
                            <form method="POST" action="{{ route('admin.portofolio.publikasikan', $portofolio) }}">
                                @csrf
                                <button class="btn btn-primary w-full"><i class="bi bi-megaphone"></i>Publikasikan ke Showcase</button>
                            </form>
                            <p class="text-xs text-slate-500">
                                Entri tampil di halaman publik hanya jika mahasiswa juga memberi persetujuan (konsen profil &amp; entri).
                            </p>
                        @elseif ($portofolio->status === 'dipublikasikan')
                            <form method="POST" action="{{ route('admin.portofolio.batalkan', $portofolio) }}">
                                @csrf
                                <button class="btn btn-outline w-full"><i class="bi bi-arrow-counterclockwise"></i>Batalkan Publikasi</button>
                            </form>
                        @else
                            <p class="text-sm text-slate-500">Entri dapat dipublikasikan setelah berstatus <strong>Diverifikasi</strong>.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
