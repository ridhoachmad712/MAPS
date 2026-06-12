<?php

namespace App\Filament\Widgets;

use App\Models\Kategori;
use Filament\Widgets\ChartWidget;

class GrafikKategori extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected ?string $heading = 'Capaian Terverifikasi per Kategori';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $kategori = Kategori::query()
            ->withCount(['portofolio as total' => fn ($q) => $q->terverifikasi()])
            ->orderBy('kategori_id')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Capaian terverifikasi',
                    'data' => $kategori->pluck('total')->all(),
                    'backgroundColor' => '#1e3a8a',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $kategori->pluck('nama_kategori')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]],
        ];
    }
}
