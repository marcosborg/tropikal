<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')->relationship('category', 'name')->required()->searchable()->preload(),
                TextInput::make('name')->required()->maxLength(255)->live(onBlur: true)->afterStateUpdated(fn (Set $set, Get $get, ?string $state) => blank($get('slug')) ? $set('slug', Str::slug($state ?? '')) : null),
                TextInput::make('slug')->required()->unique(ignoreRecord: true)->helperText('Gerado a partir do nome; pode ser alterado antes de guardar.'),
                TextInput::make('reference'), Textarea::make('excerpt')->columnSpanFull(), RichEditor::make('description')->columnSpanFull(),
                TextInput::make('price')->label('Preço indicativo')->numeric()->prefix('€')->helperText('Valor apresentado como indicativo e sujeito a confirmação.'),
                TextInput::make('compare_at_price')->label('Preço anterior')->numeric()->prefix('€'),
                Toggle::make('is_promotion')->label('Promoção ativa'), DateTimePicker::make('promotion_starts_at')->label('Início da promoção'), DateTimePicker::make('promotion_ends_at')->label('Fim da promoção'),
                Toggle::make('is_purchasable')->label('Compra online ativa'), Toggle::make('track_stock')->label('Controlar stock'), TextInput::make('stock_quantity')->label('Stock')->numeric()->minValue(0), TextInput::make('tax_rate')->label('IVA')->numeric()->suffix('%')->default(23), TextInput::make('weight_grams')->label('Peso')->numeric()->suffix('g'),
                Repeater::make('features')->schema([TextInput::make('label')->required(), TextInput::make('value')->required()])->columns(2)->columnSpanFull(), Toggle::make('is_featured')->label('Destaque na homepage'), Toggle::make('is_published')->default(true), TextInput::make('sort_order')->numeric()->default(0),
                Repeater::make('variants')->relationship()->schema([TextInput::make('name')->required(), TextInput::make('sku')->label('SKU / referência'), TextInput::make('price')->numeric()->prefix('€'), KeyValue::make('options')->label('Opções técnicas')->keyLabel('Propriedade')->valueLabel('Valor')->addActionLabel('Adicionar opção')->columnSpanFull(), Toggle::make('is_available')->label('Disponível')->default(true), Toggle::make('track_stock')->label('Controlar stock'), TextInput::make('stock_quantity')->label('Stock')->numeric()->minValue(0), TextInput::make('sort_order')->numeric()->default(0)])->columns(2)->columnSpanFull(),
                Repeater::make('images')->relationship()->schema([FileUpload::make('path')->image()->disk('public')->directory('products')->required()->maxSize(10240)->rules(['dimensions:min_width=1000'])->imageEditor()->imagePreviewHeight('220')->helperText('JPG, PNG ou WebP com pelo menos 1000 px de largura.'), TextInput::make('alt_text'), Toggle::make('is_feature_approved')->label('Aprovada para destaque')->helperText('Ative apenas depois de confirmar resolução, recorte e qualidade editorial.'), TextInput::make('sort_order')->numeric()->default(0)])->columnSpanFull(),
                Repeater::make('documents')->relationship()->schema([TextInput::make('title')->required(), FileUpload::make('path')->disk('public')->directory('manuals')->acceptedFileTypes(['application/pdf'])->maxSize(51200)->required(), TextInput::make('sort_order')->numeric()->default(0)])->columnSpanFull(), TextInput::make('meta_title')->columnSpanFull(), Textarea::make('meta_description')->columnSpanFull(),
            ]);
    }
}
