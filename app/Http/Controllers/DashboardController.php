<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Mahasiswa;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isMahasiswa()) {
            return $this->dashboardMahasiswa($user);
        }

        return $this->dashboardAdmin();
    }

    private function dashboardMahasiswa($user)
    {
        $mahasiswa = $user->mahasiswa;

        abort_unless($mahasiswa, 403, 'Akun Anda belum terhubung dengan data mahasiswa.');

        $statusCounts = $mahasiswa->portofolio()
            ->select('status', DB::raw('COUNT(*) AS total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Profil capaian: dihitung otomatis dari entri terverifikasi per kategori
        $perKategori = Kategori::query()
            ->withCount(['portofolio as total' => fn ($q) => $q
                ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->terverifikasi()])
            ->orderBy('kategori_id')
            ->get();

        $terbaru = $mahasiswa->portofolio()
            ->with(['kategori', 'verifikasi'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('dashboard.mahasiswa', compact('mahasiswa', 'statusCounts', 'perKategori', 'terbaru'));
    }

    private function dashboardAdmin()
    {
        // Seluruh statistik dihitung agregat (COUNT/GROUP BY), tidak disimpan manual
        $totalMahasiswaAktif = Mahasiswa::whereHas('portofolio', fn ($q) => $q->terverifikasi())->count();
        $totalTerverifikasi = Portofolio::terverifikasi()->count();
        $totalEntri = Portofolio::count();
        $antrianVerifikasi = Portofolio::where('status', 'diajukan')->count();

        $perKategori = Kategori::query()
            ->withCount(['portofolio as total' => fn ($q) => $q->terverifikasi()])
            ->orderBy('kategori_id')
            ->get();

        $perAngkatan = Portofolio::terverifikasi()
            ->join('mahasiswa', 'mahasiswa.mahasiswa_id', '=', 'portofolio.mahasiswa_id')
            ->select('mahasiswa.angkatan', DB::raw('COUNT(*) AS total'))
            ->groupBy('mahasiswa.angkatan')
            ->orderBy('mahasiswa.angkatan')
            ->pluck('total', 'angkatan');

        $perLevel = Portofolio::terverifikasi()
            ->select('level', DB::raw('COUNT(*) AS total'))
            ->groupBy('level')
            ->pluck('total', 'level');

        $trenTahun = Portofolio::terverifikasi()
            ->select('tahun_pencapaian', DB::raw('COUNT(*) AS total'))
            ->groupBy('tahun_pencapaian')
            ->orderBy('tahun_pencapaian')
            ->pluck('total', 'tahun_pencapaian');

        $topMahasiswa = Mahasiswa::query()
            ->withCount(['portofolio as total_terverifikasi' => fn ($q) => $q->terverifikasi()])
            ->whereHas('portofolio', fn ($q) => $q->terverifikasi())
            ->orderByDesc('total_terverifikasi')
            ->take(10)
            ->get();

        return view('dashboard.admin', compact(
            'totalMahasiswaAktif',
            'totalTerverifikasi',
            'totalEntri',
            'antrianVerifikasi',
            'perKategori',
            'perAngkatan',
            'perLevel',
            'trenTahun',
            'topMahasiswa',
        ));
    }
}
