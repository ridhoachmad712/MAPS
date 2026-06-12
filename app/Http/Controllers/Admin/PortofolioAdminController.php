<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Mahasiswa;
use App\Models\Portofolio;
use Illuminate\Http\Request;

class PortofolioAdminController extends Controller
{
    public function index(Request $request)
    {
        $portofolio = $this->queryTersaring($request)
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        $kategori = Kategori::orderBy('kategori_id')->get();
        $daftarTahun = Portofolio::select('tahun_pencapaian')->distinct()->orderByDesc('tahun_pencapaian')->pluck('tahun_pencapaian');
        $daftarAngkatan = Mahasiswa::select('angkatan')->distinct()->orderByDesc('angkatan')->pluck('angkatan');

        return view('admin.portofolio.index', compact('portofolio', 'kategori', 'daftarTahun', 'daftarAngkatan'));
    }

    /**
     * Ekspor laporan CSV mengikuti filter aktif di daftar.
     */
    public function export(Request $request)
    {
        $portofolio = $this->queryTersaring($request)->latest('updated_at')->get();

        $namaBerkas = 'laporan-portofolio-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($portofolio) {
            $keluaran = fopen('php://output', 'w');

            // BOM UTF-8 agar Excel membaca aksara dengan benar
            fwrite($keluaran, "\xEF\xBB\xBF");

            fputcsv($keluaran, [
                'NIM', 'Nama Lengkap', 'Angkatan', 'Kode Kategori', 'Kategori',
                'Judul Capaian', 'Tahun', 'Level', 'Penyelenggara', 'Peran/Capaian',
                'Status', 'Tampil Publik', 'Tanggal Input',
            ]);

            foreach ($portofolio as $p) {
                fputcsv($keluaran, [
                    $p->mahasiswa->nim,
                    $p->mahasiswa->nama_lengkap,
                    $p->mahasiswa->angkatan,
                    $p->kategori->kode,
                    $p->kategori->nama_kategori,
                    $p->judul,
                    $p->tahun_pencapaian,
                    $p->levelLabel(),
                    $p->penyelenggara,
                    $p->peran_capaian,
                    $p->statusLabel(),
                    $p->is_publik ? 'Ya' : 'Tidak',
                    $p->created_at?->format('Y-m-d H:i'),
                ]);
            }

            fclose($keluaran);
        }, $namaBerkas, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function queryTersaring(Request $request)
    {
        return Portofolio::with(['mahasiswa', 'kategori'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_id', $request->input('kategori')))
            ->when($request->filled('level'), fn ($q) => $q->where('level', $request->input('level')))
            ->when($request->filled('tahun'), fn ($q) => $q->where('tahun_pencapaian', $request->input('tahun')))
            ->when($request->filled('angkatan'), fn ($q) => $q
                ->whereHas('mahasiswa', fn ($m) => $m->where('angkatan', $request->input('angkatan'))))
            ->when($request->filled('q'), function ($q) use ($request) {
                $cari = $request->input('q');
                $q->where(fn ($w) => $w
                    ->where('judul', 'like', "%{$cari}%")
                    ->orWhereHas('mahasiswa', fn ($m) => $m
                        ->where('nama_lengkap', 'like', "%{$cari}%")
                        ->orWhere('nim', 'like', "%{$cari}%")));
            });
    }
}
