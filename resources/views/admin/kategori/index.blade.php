@extends('layouts.app')

@section('judul', 'Kategori Portofolio')

@section('konten')
    <div class="page-header mb-4">
        <div class="row align-items-center g-2">
            <div class="col">
                <h1 class="page-title">Kategori Portofolio</h1>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah-kategori">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Kategori
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-tambah-kategori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.kategori.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title">Tambah Kategori</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body d-grid gap-3">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th style="width: 6rem;">Kode</th>
                        <th>Nama Kategori</th>
                        <th class="d-none d-md-table-cell">Deskripsi</th>
                        <th class="text-end">Jumlah Entri</th>
                        <th class="text-end" style="width: 8rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategori as $k)
                        <tr>
                            <td><span class="badge bg-primary text-primary-fg">{{ $k->kode }}</span></td>
                            <td class="fw-semibold">{{ $k->nama_kategori }}</td>
                            <td class="d-none d-md-table-cell text-secondary small">{{ $k->deskripsi }}</td>
                            <td class="text-end">{{ $k->portofolio_count }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <button class="btn btn-sm btn-outline-primary btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#modal-ubah-kategori-{{ $k->kategori_id }}" aria-label="Ubah kategori">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.kategori.destroy', $k) }}"
                                          onsubmit="return confirm('Hapus kategori {{ $k->nama_kategori }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger btn-icon" @disabled($k->portofolio_count > 0) aria-label="Hapus kategori">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="modal fade" id="modal-ubah-kategori-{{ $k->kategori_id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form method="POST" action="{{ route('admin.kategori.update', $k) }}" class="modal-content text-start">
                                            @csrf @method('PUT')
                                            <div class="modal-header">
                                                <h2 class="modal-title">Ubah Kategori {{ $k->kode }}</h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body d-grid gap-3">
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
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
