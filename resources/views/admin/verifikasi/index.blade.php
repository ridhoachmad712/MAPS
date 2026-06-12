@extends('layouts.app')

@section('judul', 'Antrian Verifikasi')

@section('konten')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-extrabold text-navy-700">Antrian Verifikasi</h1>
        <form method="GET" class="flex gap-2">
            <input type="text" name="q" class="form-control form-control-sm w-64" placeholder="Cari judul / nama / NIM..."
                   value="{{ request('q') }}">
            <button class="btn btn-sm btn-maps"><i class="bi bi-search"></i></button>
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
                        <th>Bukti</th>
                        <th>Diajukan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($antrian as $p)
                        <tr>
                            <td>
                                <div class="font-semibold">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <div class="text-xs text-slate-500">{{ $p->mahasiswa->nim }} · {{ $p->mahasiswa->angkatan }}</div>
                            </td>
                            <td>{{ $p->judul }}</td>
                            <td><span class="badge badge-soft">{{ $p->kategori->kode }}</span></td>
                            <td>{{ $p->tahun_pencapaian }}</td>
                            <td><span class="badge badge-level-{{ $p->level }}">{{ $p->levelLabel() }}</span></td>
                            <td>{{ $p->bukti->count() }} berkas</td>
                            <td class="text-xs text-slate-500">{{ $p->updated_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="flex justify-end">
                                    <a href="{{ route('admin.verifikasi.show', $p) }}" class="btn btn-sm btn-maps">
                                        <i class="bi bi-search"></i>Periksa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="bi bi-check2-circle mb-2 block text-4xl text-green-500"></i>
                                Tidak ada entri yang menunggu verifikasi. Kerja bagus!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($antrian->hasPages())
            <div class="border-t border-slate-100 px-5 py-3">{{ $antrian->links() }}</div>
        @endif
    </div>
@endsection
