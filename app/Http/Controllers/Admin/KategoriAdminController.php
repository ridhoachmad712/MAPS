<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriAdminController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('portofolio')->orderBy('kategori_id')->get();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:10', 'unique:kategori,kode'],
            'nama_kategori' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [], ['nama_kategori' => 'nama kategori']);

        Kategori::create($data);

        return back()->with('sukses', 'Kategori ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:10', 'unique:kategori,kode,'.$kategori->kategori_id.',kategori_id'],
            'nama_kategori' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [], ['nama_kategori' => 'nama kategori']);

        $kategori->update($data);

        return back()->with('sukses', 'Kategori diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->portofolio()->exists()) {
            return back()->with('gagal', 'Kategori tidak dapat dihapus karena masih dipakai portofolio.');
        }

        $kategori->delete();

        return back()->with('sukses', 'Kategori dihapus.');
    }
}
