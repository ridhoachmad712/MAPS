<?php

namespace App\Filament\Resources\Mahasiswas\Tables;

use App\Models\Mahasiswa;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MahasiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('angkatan')
                    ->label('Angkatan')
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('portofolio_count')
                    ->label('Portofolio')
                    ->counts('portofolio')
                    ->badge(),
                IconColumn::make('konsen_publik')
                    ->label('Konsen Publik')
                    ->boolean(),
                IconColumn::make('user.is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('angkatan')
                    ->label('Angkatan')
                    ->options(fn (): array => Mahasiswa::query()
                        ->distinct()
                        ->orderByDesc('angkatan')
                        ->pluck('angkatan', 'angkatan')
                        ->all()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus mahasiswa?')
                    ->modalDescription('Akun beserta seluruh portofolio dan buktinya ikut terhapus. Tindakan ini tidak dapat dibatalkan.')
                    ->using(fn (Mahasiswa $record) => $record->user->delete()),
            ])
            ->defaultSort('nama_lengkap');
    }
}
