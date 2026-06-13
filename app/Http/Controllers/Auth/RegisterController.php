<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nim' => ['required', 'string', 'max:20', 'unique:mahasiswa,nim', 'unique:users,username'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:'.(date('Y') + 1)],
            'program_studi' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [], [
            'nim' => 'NIM',
            'nama_lengkap' => 'nama lengkap',
            'program_studi' => 'program studi',
            'password' => 'kata sandi',
        ]);

        // Akun dibuat nonaktif & menunggu — belum bisa masuk sebelum admin menyetujui
        DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['nim'],
                'email' => $data['email'],
                'password_hash' => $data['password'],
                'role' => 'mahasiswa',
                'is_active' => false,
                'status_pendaftaran' => 'menunggu',
            ]);

            $user->mahasiswa()->create([
                'nim' => $data['nim'],
                'nama_lengkap' => $data['nama_lengkap'],
                'angkatan' => $data['angkatan'],
                'program_studi' => $data['program_studi'],
            ]);
        });

        return redirect()->route('login')->with('sukses',
            'Pendaftaran berhasil dikirim. Akun Anda akan dapat digunakan setelah disetujui admin prodi.');
    }
}
