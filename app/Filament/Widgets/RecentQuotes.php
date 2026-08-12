<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\QuoteRequest;
use App\Filament\Resources\QuoteRequests\QuoteRequestResource;
use Filament\Tables\Columns\TextColumn;

class RecentQuotes extends TableWidget
{
    protected static ?string $heading = 'Pedidos recentes';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => QuoteRequest::query()->with('product')->latest()->limit(8))
            ->columns([
                TextColumn::make('created_at')->label('Recebido')->dateTime('d/m/Y H:i'),
                TextColumn::make('name')->label('Cliente')->searchable(),
                TextColumn::make('product.name')->label('Produto')->placeholder('Pedido geral'),
                TextColumn::make('status')->label('Estado')->badge()->formatStateUsing(fn(string $state)=>match($state){'new'=>'Novo','contacted'=>'Contactado','closed'=>'Fechado',default=>$state})->color(fn(string $state)=>match($state){'new'=>'warning','contacted'=>'info','closed'=>'success',default=>'gray'}),
            ])
            ->recordUrl(fn(QuoteRequest $record)=>QuoteRequestResource::getUrl('edit',['record'=>$record]))
            ->emptyStateHeading('Ainda não existem pedidos')
            ->emptyStateDescription('Os pedidos enviados pelo site aparecerão aqui.')
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
