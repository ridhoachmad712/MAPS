<?php

namespace App\Filament\Exports;

use App\Models\Portofolio;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PortofolioExporter extends Exporter
{
    protected static ?string $model = Portofolio::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('mahasiswa.nim')
                ->label('NIM'),
            ExportColumn::make('mahasiswa.nama_lengkap')
                ->label('Nama Lengkap'),
            ExportColumn::make('mahasiswa.angkatan')
                ->label('Angkatan'),
            ExportColumn::make('kategori.kode')
                ->label('Kode Kategori'),
            ExportColumn::make('kategori.nama_kategori')
                ->label('Kategori'),
            ExportColumn::make('judul')
                ->label('Judul Capaian'),
            ExportColumn::make('tahun_pencapaian')
                ->label('Tahun'),
            ExportColumn::make('level')
                ->label('Level')
                ->formatStateUsing(fn (string $state): string => Portofolio::LEVEL_LABEL[$state] ?? $state),
            ExportColumn::make('penyelenggara')
                ->label('Penyelenggara'),
            ExportColumn::make('peran_capaian')
                ->label('Peran/Capaian'),
            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn (string $state): string => Portofolio::STATUS_LABEL[$state] ?? $state),
            ExportColumn::make('is_publik')
                ->label('Tampil Publik')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('created_at')
                ->label('Tanggal Input'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor laporan portofolio selesai: '.Number::format($export->successful_rows).' baris diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' baris gagal.';
        }

        return $body;
    }
}
