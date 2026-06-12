<?php

namespace App\Filament\Resources\Mahasiswas\Pages;

use App\Filament\Imports\MahasiswaImporter;
use App\Filament\Resources\Mahasiswas\MahasiswaResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListMahasiswas extends ListRecords
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('Impor Massal (CSV)')
                ->importer(MahasiswaImporter::class),
            CreateAction::make()
                ->label('Tambah Mahasiswa'),
        ];
    }
}
