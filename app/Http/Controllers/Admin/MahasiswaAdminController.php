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

    public function importForm()
    {
        return view('admin.mahasiswa.import');
    }

    /**
     * Impor massal dari CSV. Satu NIM satu baris: NIM yang sudah ada
     * memperbarui data, bukan menduplikasi. Akun login dibuat otomatis
     * (username = NIM, kata sandi awal = NIM).
     */
    public function import(Request $request)
    {
        $request->validate([
            'berkas' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [], ['berkas' => 'berkas CSV']);

        $handle = fopen($request->file('berkas')->getRealPath(), 'r');

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);

            return back()->with('gagal', 'Berkas CSV kosong.');
        }

        // Normalisasi nama kolom: "Nama Lengkap" -> nama_lengkap
        $header = array_map(fn ($h) => str_replace(' ', '_', strtolower(trim((string) $h))), $header);

        $wajib = ['nim', 'nama_lengkap', 'angkatan'];
        if ($hilang = array_diff($wajib, $header)) {
            fclose($handle);

            return back()->with('gagal', 'Kolom wajib tidak ditemukan di CSV: '.implode(', ', $hilang).'. Kolom tersedia: '.implode(', ', $header).'.');
        }

        $berhasil = 0;
        $gagal = [];
        $nomorBaris = 1;

        while (($baris = fgetcsv($handle)) !== false) {
            $nomorBaris++;

            if (count(array_filter($baris, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue; // lewati baris kosong
            }

            $data = array_combine($header, array_pad(array_map('trim', $baris), count($header), ''));

            $validator = validator($data, [
                'nim' => ['required', 'max:20'],
                'nama_lengkap' => ['required', 'max:255'],
                'angkatan' => ['required', 'integer', 'min:2000', 'max:2100'],
                'program_studi' => ['nullable', 'max:100'],
                'email' => ['nullable', 'email', 'max:255'],
            ]);

            if ($validator->fails()) {
                $gagal[] = 'Baris '.$nomorBaris.': '.$validator->errors()->first();

                continue;
            }

            DB::transaction(function () use ($data) {
                $email = filled($data['email'] ?? null)
                    ? $data['email']
                    : $data['nim'].'@student.unm.ac.id';

                $user = User::firstOrCreate(
                    ['username' => $data['nim']],
                    [
                        'email' => $email,
                        'password_hash' => $data['nim'],
                        'role' => 'mahasiswa',
                        'is_active' => true,
                    ],
                );

                $mahasiswa = Mahasiswa::firstOrNew(['nim' => $data['nim']]);
                $mahasiswa->nama_lengkap = $data['nama_lengkap'];
                $mahasiswa->angkatan = (int) $data['angkatan'];
                $mahasiswa->program_studi = filled($data['program_studi'] ?? null) ? $data['program_studi'] : 'Manajemen';
                $mahasiswa->user_id = $user->user_id;
                $mahasiswa->save();
            });

            $berhasil++;
        }

        fclose($handle);

        $pesan = 'Impor mahasiswa selesai: '.$berhasil.' baris berhasil diimpor.';
        if ($gagal !== []) {
            $pesan .= ' '.count($gagal).' baris gagal: '.implode(' · ', array_slice($gagal, 0, 5));
            if (count($gagal) > 5) {
                $pesan .= ' (dan '.(count($gagal) - 5).' lainnya)';
            }

            return redirect()->route('admin.mahasiswa.index')->with('gagal', $pesan);
        }

        return redirect()->route('admin.mahasiswa.index')->with('sukses', $pesan);
    }
}
