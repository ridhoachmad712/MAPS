<?php

namespace App\Http\Controllers;

use App\Models\Bukti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuktiController extends Controller
{
    /**
     * Berkas bukti disimpan di disk privat; akses dikontrol di sini:
     * admin/verifikator, pemilik, atau siapa pun jika entrinya tampil publik.
     */
    public function show(Request $request, Bukti $bukti)
    {
        // Bukti tautan tidak punya berkas fisik — buka URL-nya langsung
        if ($bukti->isTautan()) {
            abort(404);
        }

        $portofolio = $bukti->portofolio;
        $user = $request->user();

        $bolehAkses = false;

        if ($user && in_array($user->role, ['admin', 'verifikator'])) {
            $bolehAkses = true;
        } elseif ($user && $user->mahasiswa && $user->mahasiswa->mahasiswa_id === $portofolio->mahasiswa_id) {
            $bolehAkses = true;
        } else {
            $publik = in_array($portofolio->status, ['diverifikasi', 'dipublikasikan'])
                && $portofolio->is_publik
                && $portofolio->mahasiswa->konsen_publik;
            $bolehAkses = $publik;
        }

        abort_unless($bolehAkses, 403, 'Anda tidak memiliki akses ke berkas ini.');
        abort_unless(Storage::disk('local')->exists($bukti->path_file), 404);

        return Storage::disk('local')->response($bukti->path_file, $bukti->nama_file);
    }
}
