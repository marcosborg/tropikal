<?php

namespace App\Filament\Resources\OrderRequests;

use App\Filament\Resources\OrderRequests\Pages\EditOrderRequest;
use App\Filament\Resources\OrderRequests\Pages\ListOrderRequests;
use App\Models\OrderRequest;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderRequestResource extends Resource
{
    protected static ?string $model = OrderRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $navigationLabel = 'Pedidos de Encomenda';

    protected static ?string $modelLabel = 'pedido de encomenda';

    protected static ?string $pluralModelLabel = 'pedidos de encomenda';

    protected static string|\UnitEnum|null $navigationGroup = 'Comercial';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('reference')->disabled(), Select::make('status')->options(['new' => 'Novo', 'reviewing' => 'Em análise', 'proposal_sent' => 'Proposta enviada', 'confirmed' => 'Confirmado', 'unavailable' => 'Sem disponibilidade', 'closed' => 'Fechado'])->required(),
            Select::make('customer_type')->options(['private' => 'Particular', 'professional' => 'Instalador / Profissional', 'reseller' => 'Revendedor'])->disabled(), TextInput::make('name')->disabled(), TextInput::make('email')->disabled(), TextInput::make('phone')->disabled(), TextInput::make('company')->disabled(), TextInput::make('tax_number')->label('NIF')->disabled(), TextInput::make('region')->disabled(),
            TextInput::make('shipping_address.line1')->label('Morada')->disabled(), TextInput::make('shipping_address.line2')->label('Complemento')->disabled(), TextInput::make('shipping_address.postal_code')->label('Código postal')->disabled(), TextInput::make('shipping_address.city')->label('Localidade')->disabled(), TextInput::make('shipping_address.region')->label('Ilha / região')->disabled(), TextInput::make('shipping_address.country_code')->label('País')->disabled(),
            TextInput::make('subtotal')->label('Subtotal indicativo')->prefix('€')->disabled(), Textarea::make('notes')->disabled()->columnSpanFull(),
            Repeater::make('items')->relationship()->schema([TextInput::make('quantity')->disabled(), TextInput::make('product_name')->disabled(), TextInput::make('variant_name')->disabled(), TextInput::make('unit_price')->label('Preço indicativo/un.')->prefix('€')->disabled(), Textarea::make('notes')->disabled()->columnSpanFull()])->columns(4)->columnSpanFull(), Textarea::make('internal_notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('created_at')->label('Recebido')->dateTime('d/m/Y H:i')->sortable(), TextColumn::make('reference')->searchable(), TextColumn::make('name')->searchable(), TextColumn::make('customer_type')->label('Cliente'), TextColumn::make('region'), TextColumn::make('status')->badge()])->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => ListOrderRequests::route('/'), 'edit' => EditOrderRequest::route('/{record}/edit')];
    }
}
