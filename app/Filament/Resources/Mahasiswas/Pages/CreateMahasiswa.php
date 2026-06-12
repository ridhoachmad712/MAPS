<?php

namespace App\Filament\Resources\Mahasiswas\Pages;

use App\Filament\Resources\Mahasiswas\MahasiswaResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreateMahasiswa extends CreateRecord
{
    protected static string $resource = MahasiswaResource::class;

    /**
     * Mahasiswa 1:1 users — akun login dibuat dalam satu transaksi.
     */
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Model {
            $user = User::create([
                'username' => $data['nim'],
                'email' => $data['email'],
                'password_hash' => $data['password'],
                'role' => 'mahasiswa',
                'is_active' => $data['is_active'] ?? true,
            ]);

            return $user->mahasiswa()->create(
                Arr::only($data, ['nim', 'nama_lengkap', 'angkatan', 'program_studi', 'konsen_publik']),
            );
        });
    }
}
