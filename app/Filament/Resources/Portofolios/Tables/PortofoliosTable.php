<?php

namespace App\Filament\Resources\Portofolios\Tables;

use App\Models\Mahasiswa;
use App\Models\Portofolio;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PortofoliosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mahasiswa.nama_lengkap')
                    ->label('Mahasiswa')
                    ->description(fn (Portofolio $record): string => $record->mahasiswa->nim.' · '.$record->mahasiswa->angkatan)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('kategori.kode')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('tahun_pencapaian')
                    ->label('Tahun')
                    ->sortable(),
                TextColumn::make('level')
                    ->label('Level')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Portofolio::LEVEL_LABEL[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'internasional' => 'primary',
                        'nasional' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Portofolio::STATUS_LABEL[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'diverifikasi', 'dipublikasikan' => 'success',
                        'diajukan' => 'warning',
                        'ditolak' => 'danger',
                        'revisi' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Portofolio::STATUS_LABEL),
                SelectFilter::make('level')
                    ->label('Level')
                    ->options(Portofolio::LEVEL_LABEL),
                SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori'),
                SelectFilter::make('tahun_pencapaian')
                    ->label('Tahun')
                    ->options(fn (): array => Portofolio::query()
                        ->distinct()
                        ->orderByDesc('tahun_pencapaian')
                        ->pluck('tahun_pencapaian', 'tahun_pencapaian')
                        ->all()),
                SelectFilter::make('angkatan')
                    ->label('Angkatan')
                    ->options(fn (): array => Mahasiswa::query()
                        ->distinct()
                        ->orderByDesc('angkatan')
                        ->pluck('angkatan', 'angkatan')
                        ->all())
                    ->query(fn ($query, array $data) => $query->when(
                        filled($data['value'] ?? null),
                        fn ($q) => $q->whereHas('mahasiswa', fn ($m) => $m->where('angkatan', $data['value'])),
                    )),
            ])
            ->recordActions([
                Action::make('periksa')
                    ->label('Periksa')
                    ->icon('heroicon-o-magnifying-glass')
                    ->url(fn (Portofolio $record): string => route('admin.verifikasi.show', $record))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
