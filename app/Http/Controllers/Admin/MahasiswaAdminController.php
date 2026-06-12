<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaAdminController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = Mahasiswa::with('user')
            ->withCount(['portofolio as total_terverifikasi' => fn ($q) => $q->terverifikasi()])
            ->when($request->filled('q'), function ($q) use ($request) {
                $cari = $request->input('q');
                $q->where(fn ($w) => $w
                    ->where('nama_lengkap', 'like', "%{$cari}%")
                    ->orWhere('nim', 'like', "%{$cari}%"));
            })
            ->when($request->filled('angkatan'), fn ($q) => $q->where('angkatan', $request->input('angkatan')))
            ->orderBy('nama_lengkap')
            ->paginate(15)
            ->withQueryString();

        $daftarAngkatan = Mahasiswa::select('angkatan')->distinct()->orderByDesc('angkatan')->pluck('angkatan');

        return view('admin.mahasiswa.index', compact('mahasiswa', 'daftarAngkatan'));
    }

    public function create()
    {
        return view('admin.mahasiswa.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim' => ['required', 'string', 'max:20', 'unique:mahasiswa,nim'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:2100'],
            'program_studi' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ], [], [
            'nama_lengkap' => 'nama lengkap',
            'program_studi' => 'program studi',
            'password' => 'kata sandi',
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['nim'],
                'email' => $data['email'],
                'password_hash' => $data['password'],
                'role' => 'mahasiswa',
                'is_active' => true,
            ]);

            $user->mahasiswa()->create([
                'nim' => $data['nim'],
                'nama_lengkap' => $data['nama_lengkap'],
                'angkatan' => $data['angkatan'],
                'program_studi' => $data['program_studi'],
            ]);
        });

        return redirect()->route('admin.mahasiswa.index')
            ->with('sukses', 'Akun mahasiswa berhasil dibuat. Username = NIM.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user');

        return view('admin.mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $data = $request->validate([
            'nim' => ['required', 'string', 'max:20', 'unique:mahasiswa,nim,'.$mahasiswa->mahasiswa_id.',mahasiswa_id'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:2100'],
            'program_studi' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$mahasiswa->user_id.',user_id'],
            'password' => ['nullable', 'string', 'min:8'],
            'is_active' => ['nullable', 'boolean'],
        ], [], [
            'nama_lengkap' => 'nama lengkap',
            'program_studi' => 'program studi',
            'password' => 'kata sandi baru',
        ]);

        DB::transaction(function () use ($data, $request, $mahasiswa) {
            $mahasiswa->update([
                'nim' => $data['nim'],
                'nama_lengkap' => $data['nama_lengkap'],
                'angkatan' => $data['angkatan'],
                'program_studi' => $data['program_studi'],
            ]);

            $userData = [
                'username' => $data['nim'],
                'email' => $data['email'],
                'is_active' => $request->boolean('is_active'),
            ];

            if (! empty($data['password'])) {
                $userData['password_hash'] = $data['password'];
            }

            $mahasiswa->user->update($userData);
        });

        return redirect()->route('admin.mahasiswa.index')
            ->with('sukses', 'Data mahasiswa diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        // Menghapus user akan menghapus mahasiswa + portofolio + bukti (cascade)
        $mahasiswa->user->delete();

        return redirect()->route('admin.mahasiswa.index')
            ->with('sukses', 'Akun mahasiswa beserta portofolionya dihapus.');
    }
}
