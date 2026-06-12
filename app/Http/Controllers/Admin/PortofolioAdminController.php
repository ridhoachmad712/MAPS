<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Portofolio;
use Illuminate\Http\Request;

class PortofolioAdminController extends Controller
{
    public function index(Request $request)
    {
        $portofolio = Portofolio::with(['mahasiswa', 'kategori'])
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
            })
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        $kategori = Kategori::orderBy('kategori_id')->get();
        $daftarTahun = Portofolio::select('tahun_pencapaian')->distinct()->orderByDesc('tahun_pencapaian')->pluck('tahun_pencapaian');
        $daftarAngkatan = \App\Models\Mahasiswa::select('angkatan')->distinct()->orderByDesc('angkatan')->pluck('angkatan');

        return view('admin.portofolio.index', compact('portofolio', 'kategori', 'daftarTahun', 'daftarAngkatan'));
    }
}
