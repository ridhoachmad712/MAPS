@extends('layouts.app')

@section('judul', 'Portofolio Saya')

@section('konten')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-extrabold text-navy-700">Portofolio Saya</h1>
        <a href="{{ route('portofolio.create') }}" class="btn btn-maps"><i class="bi bi-plus-circle"></i>Tambah Portofolio</a>
    </div>

    <div class="card mb-4">
        <form method="GET" class="flex flex-wrap items-end gap-3 px-5 py-4">
            <div class="w-full sm:w-52">
                <label class="form-label text-xs">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua status</option>
                    @foreach (\App\Models\Portofolio::STATUS_LABEL as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(request('status') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-64">
                <label class="form-label text-xs">Kategori</label>
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua kategori</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->kategori_id }}" @selected(request('kategori') == $k->kategori_id)>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-sm btn-maps"><i class="bi bi-funnel"></i>Saring</button>
                <a href="{{ route('portofolio.index') }}" class="btn btn-sm btn-outline">Atur Ulang</a>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table-maps">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Level</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Publik</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($portofolio as $p)
                        <tr>
                            <td>
                                <a href="{{ route('portofolio.show', $p) }}" class="font-semibold text-navy-700 hover:underline">{{ $p->judul }}</a>
                            </td>
                            <td><span class="badge badge-soft">{{ $p->kategori->kode }}</span></td>
                            <td>{{ $p->tahun_pencapaian }}</td>
                            <td><span class="badge badge-level-{{ $p->level }}">{{ $p->levelLabel() }}</span></td>
                            <td>{{ $p->bukti->count() }} berkas</td>
                            <td><span class="badge badge-{{ $p->statusBadge() }}">{{ $p->statusLabel() }}</span></td>
                            <td>
                                @if ($p->is_publik)
                                    <i class="bi bi-eye-fill text-green-600" title="Disetujui tampil publik"></i>
                                @else
                                    <i class="bi bi-eye-slash text-slate-400" title="Tidak tampil publik"></i>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end gap-1.5">
                                    <a href="{{ route('portofolio.show', $p) }}" class="btn btn-sm btn-outline" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($p->bisaDieditMahasiswa())
                                        <a href="{{ route('portofolio.edit', $p) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <i class="bi bi-journal-plus mb-2 block text-4xl text-slate-300"></i>
                                <p class="font-semibold text-slate-600">Belum ada portofolio yang cocok</p>
                                @if (request()->hasAny(['status', 'kategori']))
                                    <p class="mt-1 text-sm text-slate-400">Coba longgarkan filter, atau tambah capaian baru.</p>
                                    <a href="{{ route('portofolio.index') }}" class="btn btn-sm btn-outline mt-4">Hapus Filter</a>
                                @else
                                    <p class="mt-1 text-sm text-slate-400">Mulai arsipkan prestasi, sertifikasi, organisasi, dan capaian lainnya.</p>
                                @endif
                                <a href="{{ route('portofolio.create') }}" class="btn btn-sm btn-maps mt-4">
                                    <i class="bi bi-plus-circle"></i>Tambah Portofolio
                                </a>
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
