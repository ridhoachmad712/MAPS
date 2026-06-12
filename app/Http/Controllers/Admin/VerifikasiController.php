<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portofolio;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $antrian = Portofolio::with(['mahasiswa', 'kategori', 'bukti'])
            ->where('status', 'diajukan')
            ->when($request->filled('q'), function ($q) use ($request) {
                $cari = $request->input('q');
                $q->where(fn ($w) => $w
                    ->where('judul', 'like', "%{$cari}%")
                    ->orWhereHas('mahasiswa', fn ($m) => $m
                        ->where('nama_lengkap', 'like', "%{$cari}%")
                        ->orWhere('nim', 'like', "%{$cari}%")));
            })
            ->oldest('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.verifikasi.index', compact('antrian'));
    }

    public function show(Portofolio $portofolio)
    {
        $portofolio->load(['mahasiswa.user', 'kategori', 'bukti', 'verifikasi.verifikator']);

        return view('admin.verifikasi.show', compact('portofolio'));
    }

    public function proses(Request $request, Portofolio $portofolio)
    {
        abort_unless($portofolio->status === 'diajukan', 403,
            'Hanya entri berstatus Diajukan yang dapat diverifikasi.');

        $request->validate([
            'hasil' => ['required', 'in:diverifikasi,ditolak,revisi'],
            'catatan' => ['required_unless:hasil,diverifikasi', 'nullable', 'string', 'max:2000'],
        ], [
            'catatan.required_unless' => 'Catatan wajib diisi jika hasil Ditolak atau Perlu Revisi.',
        ], ['hasil' => 'hasil verifikasi', 'catatan' => 'catatan']);

        $portofolio->verifikasi()->create([
            'verifikator_id' => $request->user()->user_id,
            'hasil' => $request->input('hasil'),
            'catatan' => $request->input('catatan'),
            'tanggal_verifikasi' => now(),
        ]);

        $portofolio->update(['status' => $request->input('hasil')]);

        $pesan = match ($request->input('hasil')) {
            'diverifikasi' => 'Entri berhasil diverifikasi.',
            'ditolak' => 'Entri ditolak dengan catatan.',
            'revisi' => 'Entri dikembalikan untuk direvisi.',
        };

        return redirect()->route('admin.verifikasi.index')->with('sukses', $pesan);
    }

    /**
     * Penerbitan ke showcase (khusus admin, dibatasi di route).
     */
    public function publikasikan(Portofolio $portofolio)
    {
        abort_unless($portofolio->status === 'diverifikasi', 403,
            'Hanya entri terverifikasi yang dapat dipublikasikan.');

        $portofolio->update(['status' => 'dipublikasikan', 'is_publik' => true]);

        return back()->with('sukses', 'Entri dipublikasikan ke halaman showcase.');
    }

    public function batalkanPublikasi(Portofolio $portofolio)
    {
        abort_unless($portofolio->status === 'dipublikasikan', 403,
            'Entri ini tidak sedang dipublikasikan.');

        $portofolio->update(['status' => 'diverifikasi']);

        return back()->with('sukses', 'Publikasi entri dibatalkan (status kembali Diverifikasi).');
    }
}
