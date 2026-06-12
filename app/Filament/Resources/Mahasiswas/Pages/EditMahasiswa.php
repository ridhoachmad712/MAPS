<?php

namespace App\Filament\Resources\Mahasiswas\Pages;

use App\Filament\Resources\Mahasiswas\MahasiswaResource;
use App\Models\Mahasiswa;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EditMahasiswa extends EditRecord
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus')
                ->modalHeading('Hapus mahasiswa?')
                ->modalDescription('Akun beserta seluruh portofolio dan buktinya ikut terhapus. Tindakan ini tidak dapat dibatalkan.')
                ->using(fn (Mahasiswa $record) => $record->user->delete()),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['email'] = $this->getRecord()->user->email ?? '';
        $data['is_active'] = $this->getRecord()->user->is_active ?? true;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data): Model {
            $record->update(Arr::only($data, ['nim', 'nama_lengkap', 'angkatan', 'program_studi', 'konsen_publik']));

            $dataUser = [
                'username' => $data['nim'],
                'email' => $data['email'],
                'is_active' => $data['is_active'] ?? true,
            ];

            if (filled($data['password'] ?? null)) {
                $dataUser['password_hash'] = $data['password'];
            }

            $record->user->update($dataUser);

            return $record;
        });
    }
}
