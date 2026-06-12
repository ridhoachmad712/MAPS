<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->label('Nama Pengguna')
                    ->required()
                    ->maxLength(50)
                    ->alphaDash()
                    ->unique(ignoreRecord: true),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('role')
                    ->label('Peran')
                    ->options([
                        'admin' => 'Admin Prodi',
                        'verifikator' => 'Verifikator (Dosen)',
                    ])
                    ->default('verifikator')
                    ->required(),
                TextInput::make('password_hash')
                    ->label('Kata Sandi')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->helperText(fn (string $operation): string => $operation === 'create'
                        ? 'Minimal 8 karakter.'
                        : 'Kosongkan jika kata sandi tidak diganti.'),
                Toggle::make('is_active')
                    ->label('Akun aktif')
                    ->default(true),
            ]);
    }
}
