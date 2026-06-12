<?php

namespace App\Filament\Resources\Mahasiswas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MahasiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nim')
                    ->label('NIM')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true)
                    ->helperText('NIM juga menjadi nama pengguna untuk masuk.'),
                TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                TextInput::make('angkatan')
                    ->label('Angkatan')
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->default(date('Y'))
                    ->required(),
                TextInput::make('program_studi')
                    ->label('Program Studi')
                    ->required()
                    ->maxLength(100)
                    ->default('Manajemen'),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(table: 'users', column: 'email', ignorable: fn ($record) => $record?->user),
                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->helperText(fn (string $operation): string => $operation === 'create'
                        ? 'Kata sandi awal — sampaikan ke mahasiswa untuk diganti setelah masuk pertama.'
                        : 'Kosongkan jika kata sandi tidak diganti.'),
                Toggle::make('konsen_publik')
                    ->label('Persetujuan tampil publik')
                    ->helperText('Izin menampilkan nama & capaian terverifikasi di showcase.'),
                Toggle::make('is_active')
                    ->label('Akun aktif')
                    ->default(true),
            ]);
    }
}
