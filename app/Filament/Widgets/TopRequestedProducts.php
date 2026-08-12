<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Filament\Resources\Products\ProductResource;
use Filament\Tables\Columns\TextColumn;

class TopRequestedProducts extends TableWidget
{
    protected static ?string $heading = 'Produtos mais solicitados';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Product::query()->withCount('quoteRequests')->orderByDesc('quote_requests_count')->limit(8))
            ->columns([
                TextColumn::make('name')->label('Produto'),TextColumn::make('category.name')->label('Categoria'),TextColumn::make('quote_requests_count')->label('Pedidos')->badge(),
            ])
            ->recordUrl(fn(Product $record)=>ProductResource::getUrl('edit',['record'=>$record]))
            ->emptyStateHeading('Ainda não existem produtos')
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
