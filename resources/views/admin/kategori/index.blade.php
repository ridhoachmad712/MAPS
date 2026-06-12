@extends('layouts.app')

@section('judul', 'Kategori Portofolio')

@section('konten')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3" x-data="{ modalTambah: false }">
        <h1 class="text-xl font-extrabold text-navy-700">Kategori Portofolio</h1>
        <button class="btn btn-maps" @click="modalTambah = true">
            <i class="bi bi-plus-circle"></i>Tambah Kategori
        </button>

        <div x-show="modalTambah" x-transition.opacity.duration.150ms x-cloak class="modal-backdrop" @click.self="modalTambah = false">
            <form method="POST" action="{{ route('admin.kategori.store') }}" class="modal-box" @keydown.escape.window="modalTambah = false">
                @csrf
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h2 class="font-bold text-navy-700">Tambah Kategori</h2>
                    <button type="button" class="text-slate-400 hover:text-slate-600" @click="modalTambah = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="space-y-4 px-5 py-4">
                    <div>
                        <label class="form-label">Kode (singkat, unik)</label>
                        <input type="text" name="kode" class="form-control" maxlength="10" placeholder="contoh: PRES" required>
                    </div>
                    <div>
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="2" class="form-control"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-2 border-t border-slate-100 px-5 py-4">
                    <button type="button" class="px-3 text-sm text-slate-500 hover:text-slate-700" @click="modalTambah = false">Batal</button>
                    <button class="btn btn-maps">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table-maps">
                <thead>
                    <tr>
                        <th class="w-24">Kode</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-right">Jumlah Entri</th>
                        <th class="w-32 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategori as $k)
                        <tr x-data="{ modalUbah: false }">
                            <td><span class="badge badge-navy">{{ $k->kode }}</span></td>
                            <td class="font-semibold">{{ $k->nama_kategori }}</td>
                            <td class="text-xs text-slate-500">{{ $k->deskripsi }}</td>
                            <td class="text-right">{{ $k->portofolio_count }}</td>
                            <td>
                                <div class="flex justify-end gap-1.5">
                                    <button class="btn btn-sm btn-outline-primary" @click="modalUbah = true">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.kategori.destroy', $k) }}"
                                          onsubmit="return confirm('Hapus kategori {{ $k->nama_kategori }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" @disabled($k->portofolio_count > 0)>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <div x-show="modalUbah" x-transition.opacity.duration.150ms x-cloak class="modal-backdrop" @click.self="modalUbah = false">
                                    <form method="POST" action="{{ route('admin.kategori.update', $k) }}" class="modal-box text-left"
                                          @keydown.escape.window="modalUbah = false">
                                        @csrf @method('PUT')
                                        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                                            <h2 class="font-bold text-navy-700">Ubah Kategori {{ $k->kode }}</h2>
                                            <button type="button" class="text-slate-400 hover:text-slate-600" @click="modalUbah = false"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                        <div class="space-y-4 px-5 py-4">
                                            <div>
                                                <label class="form-label">Kode</label>
                                                <input type="text" name="kode" class="form-control" value="{{ $k->kode }}" maxlength="10" required>
                                            </div>
                                            <div>
                                                <label class="form-label">Nama Kategori</label>
                                                <input type="text" name="nama_kategori" class="form-control" value="{{ $k->nama_kategori }}" required>
                                            </div>
                                            <div>
                                                <label class="form-label">Deskripsi</label>
                                                <textarea name="deskripsi" rows="2" class="form-control">{{ $k->deskripsi }}</textarea>
                                            </div>
                                        </div>
                                        <div class="flex justify-end gap-2 border-t border-slate-100 px-5 py-4">
                                            <button type="button" class="px-3 text-sm text-slate-500 hover:text-slate-700" @click="modalUbah = false">Batal</button>
                                            <button class="btn btn-maps">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
