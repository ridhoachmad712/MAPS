<?php

namespace App\Filament\Resources\Portofolios;

use App\Filament\Resources\Portofolios\Pages\ListPortofolios;
use App\Filament\Resources\Portofolios\Tables\PortofoliosTable;
use App\Models\Portofolio;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Laporan baca-saja untuk rekap & ekspor. Pengelolaan entri (verifikasi,
 * publikasi) tetap di alur khusus pada /admin — bukan di panel ini.
 */
class PortofolioResource extends Resource
{
    protected static ?string $model = Portofolio::class;

    protected static ?string $slug = 'laporan';

    protected static ?string $modelLabel = 'Laporan Portofolio';

    protected static ?string $pluralModelLabel = 'Laporan Portofolio';

    protected static ?string $recordTitleAttribute = 'judul';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return PortofoliosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPortofolios::route('/'),
        ];
    }
}
