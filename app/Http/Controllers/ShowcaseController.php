<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Mahasiswa;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowcaseController extends Controller
{
    /**
     * Beranda: etalase ringkas (hero, sorotan, galeri, grafik ringkas).
     */
    public function index()
    {
        $statistik = $this->statistikRingkas();

        $sorotan = Portofolio::publik()
            ->with(['mahasiswa', 'kategori'])
            ->whereIn('level', ['internasional', 'nasional'])
            ->orderByRaw("CASE level WHEN 'internasional' THEN 1 WHEN 'nasional' THEN 2 ELSE 0 END")
            ->orderByDesc('tahun_pencapaian')
            ->take(3)
            ->get();

        $mahasiswaTop = Mahasiswa::where('konsen_publik', true)
            ->withCount(['portofolio as total_publik' => fn ($q) => $q->publik()])
            ->whereHas('portofolio', fn ($q) => $q->publik())
            ->orderByDesc('total_publik')
            ->take(6)
            ->get();

        return view('showcase.index', [
            'statistik' => $statistik,
            'sorotan' => $sorotan,
            'mahasiswaTop' => $mahasiswaTop,
            'perAngkatan' => $this->perAngkatan(),
            'trenTahun' => $this->trenTahun(),
        ]);
    }

    /**
     * Capaian: penjelajah data lengkap (filter, urut, tabel).
     */
    public function capaian(Request $request)
    {
        $entri = Portofolio::publik()
            ->with(['mahasiswa', 'kategori'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $cari = $request->input('q');
                $q->where(fn ($w) => $w
                    ->where('judul', 'like', "%{$cari}%")
                    ->orWhere('penyelenggara', 'like', "%{$cari}%")
                    ->orWhereHas('mahasiswa', fn ($m) => $m->where('nama_lengkap', 'like', "%{$cari}%")));
            })
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_id', $request->input('kategori')))
            ->when($request->filled('level'), fn ($q) => $q->where('level', $request->input('level')))
            ->when($request->filled('tahun'), fn ($q) => $q->where('tahun_pencapaian', $request->input('tahun')))
            ->when($request->filled('angkatan'), fn ($q) => $q
                ->whereHas('mahasiswa', fn ($m) => $m->where('angkatan', $request->input('angkatan'))))
            ->when($request->input('urut') === 'tahun_naik', fn ($q) => $q->orderBy('tahun_pencapaian'))
            ->when($request->input('urut') === 'tahun_turun', fn ($q) => $q->orderByDesc('tahun_pencapaian'))
            ->when($request->input('urut') === 'level_naik', fn ($q) => $q
                ->orderByRaw("CASE level WHEN 'regional' THEN 1 WHEN 'nasional' THEN 2 WHEN 'internasional' THEN 3 ELSE 0 END"))
            ->when($request->input('urut') === 'level_turun', fn ($q) => $q
                ->orderByRaw("CASE level WHEN 'regional' THEN 1 WHEN 'nasional' THEN 2 WHEN 'internasional' THEN 3 ELSE 0 END DESC"))
            ->when(! in_array($request->input('urut'), ['tahun_naik', 'tahun_turun', 'level_naik', 'level_turun']), fn ($q) => $q
                ->orderByDesc('tahun_pencapaian')
                ->orderByRaw("CASE level WHEN 'internasional' THEN 1 WHEN 'nasional' THEN 2 WHEN 'regional' THEN 3 ELSE 0 END"))
            ->paginate(15)
            ->withQueryString();

        // Sidebar filter: kategori dengan jumlah entri publik
        $kategori = Kategori::query()
            ->withCount(['portofolio as total_publik' => fn ($q) => $q->publik()])
            ->orderBy('kategori_id')
            ->get();

        $daftarTahun = Portofolio::publik()
            ->select('tahun_pencapaian')
            ->distinct()
            ->orderByDesc('tahun_pencapaian')
            ->pluck('tahun_pencapaian');

        return view('showcase.capaian', [
            'entri' => $entri,
            'kategori' => $kategori,
            'daftarTahun' => $daftarTahun,
            'daftarAngkatan' => $this->daftarAngkatanPublik(),
        ]);
    }

    /**
     * Direktori mahasiswa berprestasi.
     */
    public function daftarMahasiswa(Request $request)
    {
        $mahasiswa = Mahasiswa::where('konsen_publik', true)
            ->withCount(['portofolio as total_publik' => fn ($q) => $q->publik()])
            ->whereHas('portofolio', fn ($q) => $q->publik())
            ->when($request->filled('q'), fn ($q) => $q->where('nama_lengkap', 'like', '%'.$request->input('q').'%'))
            ->when($request->filled('angkatan'), fn ($q) => $q->where('angkatan', $request->input('angkatan')))
            ->orderByDesc('total_publik')
            ->orderBy('nama_lengkap')
            ->paginate(18)
            ->withQueryString();

        return view('showcase.mahasiswa-indeks', [
            'mahasiswa' => $mahasiswa,
            'daftarAngkatan' => $this->daftarAngkatanPublik(),
        ]);
    }

    /**
     * Statistik publik lengkap.
     */
    public function statistik()
    {
        $perKategori = Kategori::query()
            ->withCount(['portofolio as total' => fn ($q) => $q->terverifikasi()])
            ->orderBy('kategori_id')
            ->get();

        $perLevel = Portofolio::terverifikasi()
            ->select('level', DB::raw('COUNT(*) AS total'))
            ->groupBy('level')
            ->pluck('total', 'level');

        return view('showcase.statistik', [
            'statistik' => $this->statistikRingkas(),
            'perKategori' => $perKategori,
            'perLevel' => $perLevel,
            'perAngkatan' => $this->perAngkatan(),
            'trenTahun' => $this->trenTahun(),
        ]);
    }

    /**
     * Profil publik satu mahasiswa.
     */
    public function mahasiswa(Mahasiswa $mahasiswa)
    {
        abort_unless($mahasiswa->konsen_publik, 404);

        $entri = $mahasiswa->portofolio()
            ->publik()
            ->with('kategori')
            ->orderByDesc('tahun_pencapaian')
            ->get();

        abort_if($entri->isEmpty(), 404);

        // Profil capaian dihitung dari entri terverifikasi per kategori
        $perKategori = Kategori::query()
            ->withCount(['portofolio as total' => fn ($q) => $q
                ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->publik()])
            ->orderBy('kategori_id')
            ->get()
            ->filter(fn ($k) => $k->total > 0);

        return view('showcase.mahasiswa', compact('mahasiswa', 'entri', 'perKategori'));
    }

    /**
     * @return array{mahasiswa_berprestasi: int, total_capaian: int, nasional: int, internasional: int}
     */
    private function statistikRingkas(): array
    {
        return [
            'mahasiswa_berprestasi' => Mahasiswa::whereHas('portofolio', fn ($q) => $q->terverifikasi())->count(),
            'total_capaian' => Portofolio::terverifikasi()->count(),
            'nasional' => Portofolio::terverifikasi()->where('level', 'nasional')->count(),
            'internasional' => Portofolio::terverifikasi()->where('level', 'internasional')->count(),
        ];
    }

    private function perAngkatan()
    {
        return Portofolio::terverifikasi()
            ->join('mahasiswa', 'mahasiswa.mahasiswa_id', '=', 'portofolio.mahasiswa_id')
            ->select('mahasiswa.angkatan', DB::raw('COUNT(*) AS total'))
            ->groupBy('mahasiswa.angkatan')
            ->orderBy('mahasiswa.angkatan')
            ->pluck('total', 'angkatan');
    }

    private function trenTahun()
    {
        return Portofolio::terverifikasi()
            ->select('tahun_pencapaian', DB::raw('COUNT(*) AS total'))
            ->groupBy('tahun_pencapaian')
            ->orderBy('tahun_pencapaian')
            ->pluck('total', 'tahun_pencapaian');
    }

    private function daftarAngkatanPublik()
    {
        return Mahasiswa::where('konsen_publik', true)
            ->whereHas('portofolio', fn ($q) => $q->publik())
            ->select('angkatan')
            ->distinct()
            ->orderByDesc('angkatan')
            ->pluck('angkatan');
    }
}
