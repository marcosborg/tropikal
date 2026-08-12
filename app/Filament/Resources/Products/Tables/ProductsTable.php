<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\{IconColumn,TextColumn};

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),TextColumn::make('category.name')->label('Categoria')->sortable(),TextColumn::make('reference')->searchable(),TextColumn::make('price')->money('EUR')->placeholder('Sob consulta'),IconColumn::make('is_published')->boolean(),IconColumn::make('is_featured')->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
