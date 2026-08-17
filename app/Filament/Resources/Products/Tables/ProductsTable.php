<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(), TextColumn::make('category.name')->label('Categoria')->sortable(), TextColumn::make('reference')->searchable(), TextColumn::make('price')->label('Preço indicativo')->money('EUR')->placeholder('Sob consulta'), TextColumn::make('stock_quantity')->label('Stock')->placeholder('—'), IconColumn::make('is_published')->label('Publicado')->boolean(), IconColumn::make('is_featured')->label('Destaque')->boolean(), IconColumn::make('is_promotion')->label('Promoção')->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')->relationship('category', 'name')->label('Categoria')->searchable()->preload(),
                TernaryFilter::make('is_published')->label('Publicação'), TernaryFilter::make('is_featured')->label('Destaque'), TernaryFilter::make('is_purchasable')->label('Compra online'), TernaryFilter::make('is_promotion')->label('Promoção'),
                Filter::make('with_approved_image')->label('Com imagem aprovada')->query(fn (Builder $q) => $q->whereHas('images', fn ($i) => $i->where('is_feature_approved', true))),
                Filter::make('out_of_stock')->label('Sem stock')->query(fn (Builder $q) => $q->where('track_stock', true)->where(fn ($s) => $s->whereNull('stock_quantity')->orWhere('stock_quantity', '<=', 0))),
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
