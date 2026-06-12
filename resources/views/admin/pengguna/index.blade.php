@extends('layouts.app')

@section('judul', 'Akun Petugas')

@section('konten')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3" x-data="{ modalTambah: false }">
        <h1 class="text-xl font-extrabold text-navy-700">Akun Petugas (Admin &amp; Verifikator)</h1>
        <button class="btn btn-maps" @click="modalTambah = true">
            <i class="bi bi-person-plus"></i>Tambah Petugas
        </button>

        <div x-show="modalTambah" x-transition.opacity.duration.150ms x-cloak class="modal-backdrop" @click.self="modalTambah = false">
            <form method="POST" action="{{ route('admin.pengguna.store') }}" class="modal-box" @keydown.escape.window="modalTambah = false">
                @csrf
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h2 class="font-bold text-navy-700">Tambah Akun Petugas</h2>
                    <button type="button" class="text-slate-400 hover:text-slate-600" @click="modalTambah = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="space-y-4 px-5 py-4">
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
                <div class="flex justify-end gap-2 border-t border-slate-100 px-5 py-4">
                    <button type="button" class="px-3 text-sm text-slate-500 hover:text-slate-700" @click="modalTambah = false">Batal</button>
                    <button class="btn btn-maps">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table-maps">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengguna as $u)
                        <tr x-data="{ modalReset: false }">
                            <td class="font-semibold">{{ $u->username }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <span class="badge {{ $u->role === 'admin' ? 'badge-navy' : 'badge-info' }} uppercase">{{ $u->role }}</span>
                            </td>
                            <td>
                                @if ($u->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-xs text-slate-500">{{ $u->created_at?->format('d/m/Y') }}</td>
                            <td>
                                <div class="flex justify-end gap-1.5">
                                    <button class="btn btn-sm btn-outline" @click="modalReset = true" title="Reset kata sandi">
                                        <i class="bi bi-key"></i>
                                    </button>
                                    @if ($u->user_id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.pengguna.toggle', $u) }}">
                                            @csrf
                                            <button class="btn btn-sm {{ $u->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                    title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="bi {{ $u->is_active ? 'bi-person-x' : 'bi-person-check' }}"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div x-show="modalReset" x-transition.opacity.duration.150ms x-cloak class="modal-backdrop" @click.self="modalReset = false">
                                    <form method="POST" action="{{ route('admin.pengguna.reset', $u) }}" class="modal-box text-left"
                                          @keydown.escape.window="modalReset = false">
                                        @csrf
                                        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                                            <h2 class="font-bold text-navy-700">Reset Kata Sandi: {{ $u->username }}</h2>
                                            <button type="button" class="text-slate-400 hover:text-slate-600" @click="modalReset = false"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                        <div class="px-5 py-4">
                                            <label class="form-label">Kata Sandi Baru (min. 8 karakter)</label>
                                            <input type="text" name="password" class="form-control" required minlength="8">
                                        </div>
                                        <div class="flex justify-end gap-2 border-t border-slate-100 px-5 py-4">
                                            <button type="button" class="px-3 text-sm text-slate-500 hover:text-slate-700" @click="modalReset = false">Batal</button>
                                            <button class="btn btn-maps">Reset</button>
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
