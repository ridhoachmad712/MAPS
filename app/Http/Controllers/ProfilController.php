<?php

namespace App\Http\Controllers;

use App\Support\GambarUnggahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfilController extends Controller
{
    public function edit(Request $request)
    {
        $mahasiswa = $request->user()->mahasiswa;

        return view('profil.edit', compact('mahasiswa'));
    }

    public function update(Request $request)
    {
        $mahasiswa = $request->user()->mahasiswa;

        $request->validate([
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'konsen_publik' => ['nullable', 'boolean'],
        ], [], ['foto' => 'foto profil']);

        $data = ['konsen_publik' => $request->boolean('konsen_publik')];

        if ($request->hasFile('foto')) {
            if ($mahasiswa->foto) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }
            $data['foto'] = GambarUnggahan::simpan($request->file('foto'), 'foto', 400);
        }

        $mahasiswa->update($data);

        return back()->with('sukses', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [], [
            'password_lama' => 'kata sandi lama',
            'password' => 'kata sandi baru',
        ]);

        $user = $request->user();

        if (! Hash::check($request->input('password_lama'), $user->password_hash)) {
            throw ValidationException::withMessages([
                'password_lama' => 'Kata sandi lama tidak sesuai.',
            ]);
        }

        $user->update(['password_hash' => $request->input('password')]);

        return back()->with('sukses', 'Kata sandi berhasil diganti.');
    }
}
