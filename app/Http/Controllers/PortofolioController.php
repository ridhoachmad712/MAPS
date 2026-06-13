<?php

namespace App\Http\Controllers;

use App\Models\Bukti;
use App\Models\Kategori;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortofolioController extends Controller
{
    private const ATURAN = [
        'kategori_id' => ['required', 'exists:kategori,kategori_id'],
        'judul' => ['required', 'string', 'max:255'],
        'deskripsi' => ['nullable', 'string', 'max:5000'],
        'tahun_pencapaian' => ['required', 'integer', 'min:2000', 'max:2100'],
        'level' => ['required', 'in:regional,nasional,internasional'],
        'penyelenggara' => ['nullable', 'string', 'max:255'],
        'peran_capaian' => ['nullable', 'string', 'max:255'],
        'is_publik' => ['nullable', 'boolean'],
        'tautan' => ['nullable', 'array', 'max:10'],
        'tautan.*' => ['nullable', 'url', 'max:1000'],
        'bukti' => ['nullable', 'array', 'max:5'],
        'bukti.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
    ];

    private const NAMA_ATRIBUT = [
        'kategori_id' => 'kategori',
        'judul' => 'judul',
        'tahun_pencapaian' => 'tahun pencapaian',
        'level' => 'level',
        'tautan.*' => 'tautan bukti',
        'bukti.*' => 'berkas bukti',
    ];

    public function index(Request $request)
    {
        $mahasiswa = $request->user()->mahasiswa;

        $portofolio = $mahasiswa->portofolio()
            ->with(['kategori', 'bukti'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_id', $request->input('kategori')))
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        $kategori = Kategori::orderBy('kategori_id')->get();

        return view('portofolio.index', compact('portofolio', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('kategori_id')->get();

        return view('portofolio.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $mahasiswa = $request->user()->mahasiswa;
        $data = $request->validate(self::ATURAN, [], self::NAMA_ATRIBUT);

        $portofolio = $mahasiswa->portofolio()->create([
            ...collect($data)->except(['bukti', 'tautan'])->all(),
            'is_publik' => $request->boolean('is_publik'),
            'status' => 'draft',
        ]);

        $this->simpanBukti($request, $portofolio);

        if ($request->input('aksi') === 'ajukan') {
            return $this->ajukanInternal($portofolio);
        }

        return redirect()->route('portofolio.show', $portofolio)
            ->with('sukses', 'Portofolio tersimpan sebagai draft.');
    }

    public function show(Request $request, Portofolio $portofolio)
    {
        $this->pastikanPemilik($request, $portofolio);

        $portofolio->load(['kategori', 'bukti', 'verifikasi.verifikator']);

        return view('portofolio.show', compact('portofolio'));
    }

    public function edit(Request $request, Portofolio $portofolio)
    {
        $this->pastikanPemilik($request, $portofolio);

        abort_unless($portofolio->bisaDieditMahasiswa(), 403,
            'Entri yang sudah diajukan atau diverifikasi tidak dapat diubah.');

        $kategori = Kategori::orderBy('kategori_id')->get();
        $portofolio->load('bukti');

        return view('portofolio.edit', compact('portofolio', 'kategori'));
    }

    public function update(Request $request, Portofolio $portofolio)
    {
        $this->pastikanPemilik($request, $portofolio);

        abort_unless($portofolio->bisaDieditMahasiswa(), 403,
            'Entri yang sudah diajukan atau diverifikasi tidak dapat diubah.');

        $data = $request->validate(self::ATURAN, [], self::NAMA_ATRIBUT);

        $portofolio->update([
            ...collect($data)->except(['bukti', 'tautan'])->all(),
            'is_publik' => $request->boolean('is_publik'),
        ]);

        $this->simpanBukti($request, $portofolio);

        if ($request->input('aksi') === 'ajukan') {
            return $this->ajukanInternal($portofolio);
        }

        return redirect()->route('portofolio.show', $portofolio)
            ->with('sukses', 'Perubahan tersimpan.');
    }

    public function destroy(Request $request, Portofolio $portofolio)
    {
        $this->pastikanPemilik($request, $portofolio);

        abort_unless($portofolio->bisaDieditMahasiswa(), 403,
            'Entri yang sudah diajukan atau diverifikasi tidak dapat dihapus.');

        foreach ($portofolio->bukti as $bukti) {
            if ($bukti->sumber === 'berkas' && $bukti->path_file) {
                Storage::disk('local')->delete($bukti->path_file);
            }
        }

        $portofolio->delete();

        return redirect()->route('portofolio.index')
            ->with('sukses', 'Portofolio dihapus.');
    }

    public function ajukan(Request $request, Portofolio $portofolio)
    {
        $this->pastikanPemilik($request, $portofolio);

        abort_unless($portofolio->bisaDiajukan(), 403, 'Entri ini tidak dapat diajukan.');

        return $this->ajukanInternal($portofolio);
    }

    public function tampilkanPublik(Request $request, Portofolio $portofolio)
    {
        $this->pastikanPemilik($request, $portofolio);

        $portofolio->update(['is_publik' => ! $portofolio->is_publik]);

        return back()->with('sukses', $portofolio->is_publik
            ? 'Entri disetujui untuk tampil di halaman publik.'
            : 'Entri ditarik dari halaman publik.');
    }

    public function simpanBuktiBaru(Request $request, Portofolio $portofolio)
    {
        $this->pastikanPemilik($request, $portofolio);

        abort_unless($portofolio->bisaDieditMahasiswa(), 403,
            'Bukti hanya dapat ditambah saat entri masih dapat diubah.');

        $request->validate([
            'tautan' => ['nullable', 'array', 'max:10'],
            'tautan.*' => ['nullable', 'url', 'max:1000'],
            'bukti' => ['nullable', 'array', 'max:5'],
            'bukti.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [], ['tautan.*' => 'tautan bukti', 'bukti.*' => 'berkas bukti']);

        $jumlah = $this->simpanBukti($request, $portofolio);

        if ($jumlah === 0) {
            return back()->with('gagal', 'Tempelkan minimal satu tautan atau pilih berkas untuk diunggah.');
        }

        return back()->with('sukses', 'Bukti berhasil ditambahkan.');
    }

    public function hapusBukti(Request $request, Bukti $bukti)
    {
        $portofolio = $bukti->portofolio;

        $this->pastikanPemilik($request, $portofolio);

        abort_unless($portofolio->bisaDieditMahasiswa(), 403,
            'Bukti hanya dapat dihapus saat entri masih dapat diubah.');

        if ($bukti->sumber === 'berkas' && $bukti->path_file) {
            Storage::disk('local')->delete($bukti->path_file);
        }
        $bukti->delete();

        return back()->with('sukses', 'Bukti dihapus.');
    }

    private function ajukanInternal(Portofolio $portofolio)
    {
        if ($portofolio->bukti()->count() === 0) {
            return redirect()->route('portofolio.show', $portofolio)
                ->with('gagal', 'Lampirkan minimal satu bukti (tautan atau berkas) sebelum mengajukan verifikasi.');
        }

        $portofolio->update(['status' => 'diajukan']);

        return redirect()->route('portofolio.show', $portofolio)
            ->with('sukses', 'Portofolio diajukan. Menunggu verifikasi dari prodi.');
    }

    /**
     * Simpan bukti dari dua sumber: tautan URL (default, hemat server) dan berkas unggahan.
     * Mengembalikan jumlah bukti yang berhasil ditambahkan.
     */
    private function simpanBukti(Request $request, Portofolio $portofolio): int
    {
        $jumlah = 0;

        foreach ($request->input('tautan', []) as $url) {
            $url = trim((string) $url);
            if ($url === '') {
                continue;
            }

            $bukti = $portofolio->bukti()->make([
                'sumber' => 'tautan',
                'url' => $url,
                'tipe_file' => 'tautan',
                'uploaded_at' => now(),
            ]);
            $bukti->nama_file = 'Tautan '.$bukti->layanan();
            $bukti->save();
            $jumlah++;
        }

        foreach ($request->file('bukti', []) as $file) {
            $path = $file->store('bukti/'.$portofolio->portofolio_id, 'local');

            $portofolio->bukti()->create([
                'sumber' => 'berkas',
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'tipe_file' => $file->getMimeType(),
                'uploaded_at' => now(),
            ]);
            $jumlah++;
        }

        return $jumlah;
    }

    private function pastikanPemilik(Request $request, Portofolio $portofolio): void
    {
        $mahasiswa = $request->user()->mahasiswa;

        abort_unless($mahasiswa && $portofolio->mahasiswa_id === $mahasiswa->mahasiswa_id, 403,
            'Anda hanya dapat mengelola portofolio milik sendiri.');
    }
}
