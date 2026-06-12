<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PenggunaAdminController extends Controller
{
    public function index()
    {
        $pengguna = User::whereIn('role', ['admin', 'verifikator'])
            ->orderBy('role')
            ->orderBy('username')
            ->get();

        return view('admin.pengguna.index', compact('pengguna'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username', 'alpha_dash'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,verifikator'],
            'password' => ['required', 'string', 'min:8'],
        ], [], ['username' => 'nama pengguna', 'role' => 'peran', 'password' => 'kata sandi']);

        User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => $data['password'],
            'role' => $data['role'],
            'is_active' => true,
        ]);

        return back()->with('sukses', 'Akun '.$data['role'].' berhasil dibuat.');
    }

    public function toggleAktif(Request $request, User $pengguna)
    {
        if ($pengguna->user_id === $request->user()->user_id) {
            return back()->with('gagal', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $pengguna->update(['is_active' => ! $pengguna->is_active]);

        return back()->with('sukses', 'Status akun '.$pengguna->username.' diperbarui.');
    }

    public function resetPassword(Request $request, User $pengguna)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ], [], ['password' => 'kata sandi baru']);

        $pengguna->update(['password_hash' => $request->input('password')]);

        return back()->with('sukses', 'Kata sandi '.$pengguna->username.' berhasil direset.');
    }
}
