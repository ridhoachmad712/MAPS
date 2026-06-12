<?php

namespace App\Filament\Widgets;

use App\Models\Mahasiswa;
use App\Models\Portofolio;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatistikMaps extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Mahasiswa', Mahasiswa::count())
                ->description(Mahasiswa::whereHas('portofolio', fn ($q) => $q->terverifikasi())->count().' berprestasi (≥1 terverifikasi)')
                ->icon('heroicon-o-users'),
            Stat::make('Capaian Terverifikasi', Portofolio::terverifikasi()->count())
                ->description('dari '.Portofolio::count().' total entri')
                ->icon('heroicon-o-check-badge'),
            Stat::make('Menunggu Verifikasi', Portofolio::where('status', 'diajukan')->count())
                ->description('proses di menu Verifikasi pada aplikasi utama')
                ->icon('heroicon-o-clock'),
        ];
    }
}
