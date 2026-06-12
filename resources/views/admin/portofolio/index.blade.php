@extends('layouts.app')

@section('judul', 'Semua Portofolio')

@section('konten')
    <h1 class="mb-6 text-xl font-extrabold text-navy-700">Semua Portofolio</h1>

    <div class="card mb-4">
        <form method="GET" class="flex flex-wrap items-end gap-3 px-5 py-4">
            <div class="w-full sm:w-56">
                <label class="form-label text-xs">Pencarian</label>
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Judul / nama / NIM..." value="{{ request('q') }}">
            </div>
            <div class="w-36">
                <label class="form-label text-xs">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach (\App\Models\Portofolio::STATUS_LABEL as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(request('status') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-32">
                <label class="form-label text-xs">Kategori</label>
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->kategori_id }}" @selected(request('kategori') == $k->kategori_id)>{{ $k->kode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-36">
                <label class="form-label text-xs">Level</label>
                <select name="level" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach (\App\Models\Portofolio::LEVEL_LABEL as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(request('level') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-28">
                <label class="form-label text-xs">Tahun</label>
                <select name="tahun" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach ($daftarTahun as $t)
                        <option value="{{ $t }}" @selected(request('tahun') == $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-28">
                <label class="form-label text-xs">Angkatan</label>
                <select name="angkatan" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach ($daftarAngkatan as $a)
                        <option value="{{ $a }}" @selected(request('angkatan') == $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-sm btn-maps"><i class="bi bi-funnel"></i>Saring</button>
                <a href="{{ route('admin.portofolio.index') }}" class="btn btn-sm btn-outline">Atur Ulang</a>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table-maps">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Publik</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($portofolio as $p)
                        <tr>
                            <td>
                                <div class="font-semibold">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <div class="text-xs text-slate-500">{{ $p->mahasiswa->nim }} · {{ $p->mahasiswa->angkatan }}</div>
                            </td>
                            <td>{{ $p->judul }}</td>
                            <td><span class="badge badge-soft">{{ $p->kategori->kode }}</span></td>
                            <td>{{ $p->tahun_pencapaian }}</td>
                            <td><span class="badge badge-level-{{ $p->level }}">{{ $p->levelLabel() }}</span></td>
                            <td><span class="badge badge-{{ $p->statusBadge() }}">{{ $p->statusLabel() }}</span></td>
                            <td>
                                @if ($p->is_publik && $p->mahasiswa->konsen_publik && in_array($p->status, ['diverifikasi', 'dipublikasikan']))
                                    <i class="bi bi-eye-fill text-green-600" title="Tampil di showcase"></i>
                                @else
                                    <i class="bi bi-eye-slash text-slate-400" title="Tidak tampil di showcase"></i>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    <a href="{{ route('admin.verifikasi.show', $p) }}" class="btn btn-sm btn-outline">
                                        <i class="bi bi-eye"></i>Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="bi bi-inbox mb-2 block text-4xl text-slate-300"></i>
                                Tidak ada entri yang cocok dengan filter.
                                <div class="mt-3">
                                    <a href="{{ route('admin.portofolio.index') }}" class="btn btn-sm btn-outline">Hapus Filter</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($portofolio->hasPages())
            <div class="border-t border-slate-100 px-5 py-3">{{ $portofolio->links() }}</div>
        @endif
    </div>
@endsection
