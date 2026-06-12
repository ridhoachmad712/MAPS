<?php

namespace App\Filament\Resources\Kategoris\Tables;

use App\Models\Kategori;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KategorisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->badge()
                    ->searchable(),
                TextColumn::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(70)
                    ->color('gray'),
                TextColumn::make('portofolio_count')
                    ->label('Jumlah Entri')
                    ->counts('portofolio')
                    ->badge(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Hapus')
                    // Kategori yang masih dipakai portofolio tidak boleh dihapus (FK restrict)
                    ->hidden(fn (Kategori $record): bool => $record->portofolio()->exists()),
            ])
            ->defaultSort('kategori_id');
    }
}
