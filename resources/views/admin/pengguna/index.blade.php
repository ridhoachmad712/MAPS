@extends('layouts.app')

@section('judul', 'Akun Petugas')

@section('konten')
    <div class="page-header mb-4">
        <div class="row align-items-center g-2">
            <div class="col">
                <h1 class="page-title">Akun Petugas (Admin &amp; Verifikator)</h1>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah-petugas">
                    <i class="bi bi-person-plus me-1"></i>Tambah Petugas
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-tambah-petugas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.pengguna.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title">Tambah Akun Petugas</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body d-grid gap-3">
                    <div>
                        <label class="form-label">Nama Pengguna</label>
                        <input type="text" name="username" class="form-control" placeholder="tanpa spasi" required>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Peran</label>
                        <select name="role" class="form-select" required>
                            <option value="verifikator">Verifikator (Dosen)</option>
                            <option value="admin">Admin Prodi</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Kata Sandi (min. 8 karakter)</label>
                        <input type="text" name="password" class="form-control" required minlength="8">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengguna as $u)
                        <tr>
                            <td class="fw-semibold">{{ $u->username }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <span class="badge {{ $u->role === 'admin' ? 'bg-primary text-primary-fg' : 'bg-info-lt' }} text-uppercase">{{ $u->role }}</span>
                            </td>
                            <td>
                                @if ($u->is_active)
                                    <span class="badge bg-success-lt">Aktif</span>
                                @else
                                    <span class="badge bg-danger-lt">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-secondary small">{{ $u->created_at?->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <button class="btn btn-sm btn-outline-secondary btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#modal-reset-{{ $u->user_id }}" title="Reset kata sandi" aria-label="Reset kata sandi">
                                        <i class="bi bi-key"></i>
                                    </button>
                                    @if ($u->user_id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.pengguna.toggle', $u) }}">
                                            @csrf
                                            <button class="btn btn-sm {{ $u->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} btn-icon"
                                                    title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" aria-label="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="bi {{ $u->is_active ? 'bi-person-x' : 'bi-person-check' }}"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="modal fade" id="modal-reset-{{ $u->user_id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form method="POST" action="{{ route('admin.pengguna.reset', $u) }}" class="modal-content text-start">
                                            @csrf
                                            <div class="modal-header">
                                                <h2 class="modal-title">Reset Kata Sandi: {{ $u->username }}</h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label">Kata Sandi Baru (min. 8 karakter)</label>
                                                <input type="text" name="password" class="form-control" required minlength="8">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button class="btn btn-primary">Reset</button>
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
