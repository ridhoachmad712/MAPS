<?php

namespace App\Filament\Resources\Kategoris\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KategoriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->label('Kode')
                    ->required()
                    ->maxLength(10)
                    ->unique(ignoreRecord: true)
                    ->helperText('Singkat dan unik, contoh: PRES, PKM, SERT.'),
                TextInput::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(100),
                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }
}
