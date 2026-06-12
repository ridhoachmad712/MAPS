@extends('layouts.app')

@section('judul', 'Data Mahasiswa')

@section('konten')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-extrabold text-navy-700">Data Mahasiswa</h1>
        <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-maps"><i class="bi bi-person-plus"></i>Tambah Mahasiswa</a>
    </div>

    <div class="card mb-4">
        <form method="GET" class="flex flex-wrap items-end gap-3 px-5 py-4">
            <div class="w-full sm:w-64">
                <label class="form-label text-xs">Pencarian</label>
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Nama atau NIM..." value="{{ request('q') }}">
            </div>
            <div class="w-40">
                <label class="form-label text-xs">Angkatan</label>
                <select name="angkatan" class="form-select form-select-sm">
                    <option value="">Semua angkatan</option>
                    @foreach ($daftarAngkatan as $a)
                        <option value="{{ $a }}" @selected(request('angkatan') == $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-sm btn-maps"><i class="bi bi-funnel"></i>Saring</button>
                <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-sm btn-outline">Atur Ulang</a>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table-maps">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Angkatan</th>
                        <th>Email</th>
                        <th>Konsen Publik</th>
                        <th>Status Akun</th>
                        <th class="text-right">Capaian Terverifikasi</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswa as $m)
                        <tr>
                            <td>{{ $m->nim }}</td>
                            <td class="font-semibold">{{ $m->nama_lengkap }}</td>
                            <td>{{ $m->angkatan }}</td>
                            <td class="text-xs">{{ $m->user->email ?? '—' }}</td>
                            <td>
                                @if ($m->konsen_publik)
                                    <span class="badge badge-soft-success">Setuju</span>
                                @else
                                    <span class="badge badge-soft-secondary">Tidak</span>
                                @endif
                            </td>
                            <td>
                                @if ($m->user?->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right"><span class="badge badge-success">{{ $m->total_terverifikasi }}</span></td>
                            <td>
                                <div class="flex justify-end gap-1.5">
                                    <a href="{{ route('admin.mahasiswa.edit', $m) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.mahasiswa.destroy', $m) }}"
                                          onsubmit="return confirm('Hapus {{ $m->nama_lengkap }} beserta akun dan seluruh portofolionya? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-10 text-center text-slate-400">Belum ada data mahasiswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($mahasiswa->hasPages())
            <div class="border-t border-slate-100 px-5 py-3">{{ $mahasiswa->links() }}</div>
        @endif
    </div>
@endsection
