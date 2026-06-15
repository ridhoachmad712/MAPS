<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftar = User::with('mahasiswa')
            ->where('role', 'mahasiswa')
            ->where('status_pendaftaran', 'menunggu')
            ->orderBy('created_at')
            ->paginate(15);

        return view('admin.pendaftaran.index', compact('pendaftar'));
    }

    public function setujui(User $pengguna)
    {
        abort_unless($pengguna->menungguPersetujuan(), 403, 'Akun ini tidak menunggu persetujuan.');

        $pengguna->update(['status_pendaftaran' => 'disetujui', 'is_active' => true]);

        return back()->with('sukses', 'Pendaftaran '.($pengguna->mahasiswa->nama_lengkap ?? $pengguna->username).' disetujui. Mahasiswa kini dapat masuk.');
    }

    public function setujuiSemua()
    {
        $jumlah = User::where('role', 'mahasiswa')
            ->where('status_pendaftaran', 'menunggu')
            ->update(['status_pendaftaran' => 'disetujui', 'is_active' => true]);

        if ($jumlah === 0) {
            return back()->with('gagal', 'Tidak ada pendaftaran yang menunggu persetujuan.');
        }

        return back()->with('sukses', $jumlah.' pendaftaran disetujui sekaligus. Mahasiswa kini dapat masuk.');
    }

    public function tolak(User $pengguna)
    {
        abort_unless($pengguna->menungguPersetujuan(), 403, 'Akun ini tidak menunggu persetujuan.');

        $nama = $pengguna->mahasiswa->nama_lengkap ?? $pengguna->username;

        // Hapus akun+profil agar NIM/email bebas didaftarkan ulang bila perlu
        $pengguna->delete();

        return back()->with('sukses', 'Pendaftaran '.$nama.' ditolak dan dihapus.');
    }
}
