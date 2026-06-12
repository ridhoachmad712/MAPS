<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Mahasiswa;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowcaseController extends Controller
{
    public function index(Request $request)
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
                ->orderByRaw("FIELD(level, 'regional', 'nasional', 'internasional')"))
            ->when($request->input('urut') === 'level_turun', fn ($q) => $q
                ->orderByRaw("FIELD(level, 'regional', 'nasional', 'internasional') DESC"))
            ->when(! in_array($request->input('urut'), ['tahun_naik', 'tahun_turun', 'level_naik', 'level_turun']), fn ($q) => $q
                ->orderByDesc('tahun_pencapaian')
                ->orderByRaw("FIELD(level, 'internasional', 'nasional', 'regional')"))
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

        $daftarAngkatan = Mahasiswa::where('konsen_publik', true)
            ->whereHas('portofolio', fn ($q) => $q->publik())
            ->select('angkatan')
            ->distinct()
            ->orderByDesc('angkatan')
            ->pluck('angkatan');

        // Statistik ringkas resmi: dihitung dari entri terverifikasi
        $statistik = [
            'mahasiswa_berprestasi' => Mahasiswa::whereHas('portofolio', fn ($q) => $q->terverifikasi())->count(),
            'total_capaian' => Portofolio::terverifikasi()->count(),
            'nasional' => Portofolio::terverifikasi()->where('level', 'nasional')->count(),
            'internasional' => Portofolio::terverifikasi()->where('level', 'internasional')->count(),
        ];

        $perAngkatan = Portofolio::terverifikasi()
            ->join('mahasiswa', 'mahasiswa.mahasiswa_id', '=', 'portofolio.mahasiswa_id')
            ->select('mahasiswa.angkatan', DB::raw('COUNT(*) AS total'))
            ->groupBy('mahasiswa.angkatan')
            ->orderBy('mahasiswa.angkatan')
            ->pluck('total', 'angkatan');

        $trenTahun = Portofolio::terverifikasi()
            ->select('tahun_pencapaian', DB::raw('COUNT(*) AS total'))
            ->groupBy('tahun_pencapaian')
            ->orderBy('tahun_pencapaian')
            ->pluck('total', 'tahun_pencapaian');

        // Sorotan & galeri hanya tampil di beranda tanpa filter
        $tanpaFilter = ! $request->hasAny(['q', 'kategori', 'level', 'tahun', 'angkatan', 'urut', 'page']);

        $sorotan = $tanpaFilter
            ? Portofolio::publik()
                ->with(['mahasiswa', 'kategori'])
                ->whereIn('level', ['internasional', 'nasional'])
                ->orderByRaw("FIELD(level, 'internasional', 'nasional')")
                ->orderByDesc('tahun_pencapaian')
                ->take(3)
                ->get()
            : collect();

        $mahasiswaTop = $tanpaFilter
            ? Mahasiswa::where('konsen_publik', true)
                ->withCount(['portofolio as total_publik' => fn ($q) => $q->publik()])
                ->having('total_publik', '>', 0)
                ->orderByDesc('total_publik')
                ->take(6)
                ->get()
            : collect();

        return view('showcase.index', compact(
            'entri', 'kategori', 'daftarTahun', 'daftarAngkatan', 'statistik', 'perAngkatan', 'trenTahun',
            'sorotan', 'mahasiswaTop',
        ));
    }

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
}
