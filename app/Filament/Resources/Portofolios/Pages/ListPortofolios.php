<?php

namespace App\Filament\Resources\Portofolios\Pages;

use App\Filament\Exports\PortofolioExporter;
use App\Filament\Resources\Portofolios\PortofolioResource;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListPortofolios extends ListRecords
{
    protected static string $resource = PortofolioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Ekspor (Excel/CSV)')
                ->exporter(PortofolioExporter::class),
        ];
    }
}
