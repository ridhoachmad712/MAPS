<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'admin' ? 'Admin Prodi' : 'Verifikator')
                    ->color(fn (string $state): string => $state === 'admin' ? 'primary' : 'info'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Hapus')
                    // Tidak boleh menghapus akun sendiri atau verifikator yang punya riwayat verifikasi (FK restrict)
                    ->hidden(fn (User $record): bool => $record->user_id === auth()->id()
                        || $record->verifikasi()->exists()),
            ])
            ->defaultSort('username');
    }
}
