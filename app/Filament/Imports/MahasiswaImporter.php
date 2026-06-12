<?php

namespace App\Filament\Imports;

use App\Models\Mahasiswa;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class MahasiswaImporter extends Importer
{
    protected static ?string $model = Mahasiswa::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nim')
                ->label('NIM')
                ->requiredMapping()
                ->rules(['required', 'max:20']),
            ImportColumn::make('nama_lengkap')
                ->label('Nama Lengkap')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('angkatan')
                ->label('Angkatan')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'min:2000', 'max:2100']),
            ImportColumn::make('program_studi')
                ->label('Program Studi')
                ->rules(['max:100'])
                ->fillRecordUsing(function (Mahasiswa $record, ?string $state): void {
                    $record->program_studi = filled($state) ? $state : 'Manajemen';
                }),
            ImportColumn::make('email')
                ->label('Email')
                ->rules(['nullable', 'email', 'max:255'])
                ->fillRecordUsing(function (): void {
                    // Bukan kolom mahasiswa — dipakai saat membuat akun user di beforeSave()
                }),
        ];
    }

    /**
     * Satu NIM satu baris: baris dengan NIM yang sudah ada memperbarui data, bukan menduplikasi.
     */
    public function resolveRecord(): Mahasiswa
    {
        return Mahasiswa::firstOrNew(['nim' => $this->data['nim']]);
    }

    /**
     * Buat/temukan akun login: username = NIM, kata sandi awal = NIM.
     */
    protected function beforeSave(): void
    {
        $email = filled($this->data['email'] ?? null)
            ? $this->data['email']
            : $this->data['nim'].'@student.unm.ac.id';

        $user = User::firstOrCreate(
            ['username' => $this->data['nim']],
            [
                'email' => $email,
                'password_hash' => $this->data['nim'],
                'role' => 'mahasiswa',
                'is_active' => true,
            ],
        );

        $this->record->user_id = $user->user_id;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor mahasiswa selesai: '.Number::format($import->successful_rows).' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' baris gagal.';
        }

        return $body;
    }
}
