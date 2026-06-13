@extends('layouts.app')

@section('judul', 'Pendaftaran Mahasiswa')

@section('konten')
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Pendaftaran Mahasiswa</h2>
                <p class="text-secondary mb-0">Tinjau akun mahasiswa yang mendaftar mandiri sebelum mereka dapat masuk.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Angkatan</th>
                        <th>Email</th>
                        <th>Didaftarkan</th>
                        <th class="w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendaftar as $p)
                        <tr>
                            <td>{{ $p->mahasiswa->nim ?? '—' }}</td>
                            <td class="fw-semibold">{{ $p->mahasiswa->nama_lengkap ?? '—' }}</td>
                            <td>{{ $p->mahasiswa->angkatan ?? '—' }}</td>
                            <td class="text-secondary">{{ $p->email }}</td>
                            <td class="text-secondary">{{ $p->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <form method="POST" action="{{ route('admin.pendaftaran.setujui', $p) }}"
                                          onsubmit="return confirm('Setujui pendaftaran {{ $p->mahasiswa->nama_lengkap ?? $p->username }}? Mahasiswa akan langsung dapat masuk.')">
                                        @csrf
                                        <button class="btn btn-success btn-sm">
                                            <i class="bi bi-check-lg me-1"></i>Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.pendaftaran.tolak', $p) }}"
                                          onsubmit="return confirm('Tolak dan hapus pendaftaran {{ $p->mahasiswa->nama_lengkap ?? $p->username }}? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-x-lg me-1"></i>Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty">
                                    <div class="empty-icon"><i class="bi bi-inbox fs-1 text-secondary"></i></div>
                                    <p class="empty-title">Tidak ada pendaftaran menunggu</p>
                                    <p class="empty-subtitle text-secondary">Semua pendaftaran mahasiswa sudah ditinjau.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pendaftar->hasPages())
            <div class="card-footer d-flex justify-content-end">{{ $pendaftar->links() }}</div>
        @endif
    </div>
@endsection
