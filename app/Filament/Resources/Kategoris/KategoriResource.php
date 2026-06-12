<?php

namespace App\Filament\Resources\Kategoris;

use App\Filament\Resources\Kategoris\Pages\CreateKategori;
use App\Filament\Resources\Kategoris\Pages\EditKategori;
use App\Filament\Resources\Kategoris\Pages\ListKategoris;
use App\Filament\Resources\Kategoris\Schemas\KategoriForm;
use App\Filament\Resources\Kategoris\Tables\KategorisTable;
use App\Models\Kategori;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static ?string $slug = 'kategori';

    protected static ?string $modelLabel = 'Kategori';

    protected static ?string $pluralModelLabel = 'Kategori';

    protected static ?string $recordTitleAttribute = 'nama_kategori';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return KategoriForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategorisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKategoris::route('/'),
            'create' => CreateKategori::route('/create'),
            'edit' => EditKategori::route('/{record}/edit'),
        ];
    }
}
