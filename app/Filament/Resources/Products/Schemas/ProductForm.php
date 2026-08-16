<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{FileUpload,Repeater,RichEditor,Select,TextInput,Textarea,Toggle};

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')->relationship('category','name')->required()->searchable()->preload(), TextInput::make('name')->required()->maxLength(255), TextInput::make('slug')->required()->unique(ignoreRecord:true), TextInput::make('reference'), Textarea::make('excerpt')->columnSpanFull(), RichEditor::make('description')->columnSpanFull(), TextInput::make('price')->numeric()->prefix('€'), Repeater::make('features')->schema([TextInput::make('label')->required(),TextInput::make('value')->required()])->columns(2)->columnSpanFull(), Toggle::make('is_featured'),Toggle::make('is_published')->default(true),TextInput::make('sort_order')->numeric()->default(0), Repeater::make('variants')->relationship()->schema([TextInput::make('name')->required(),TextInput::make('sku')->label('SKU / referência'),TextInput::make('price')->numeric()->prefix('€'),Toggle::make('is_available')->label('Disponível para pedido')->default(true),TextInput::make('sort_order')->numeric()->default(0)])->columns(2)->columnSpanFull(), Repeater::make('images')->relationship()->schema([FileUpload::make('path')->image()->disk('public')->directory('products')->required(),TextInput::make('alt_text'),Toggle::make('is_feature_approved')->label('Aprovada para destaque')->helperText('Use apenas imagens com resolução e qualidade editorial.'),TextInput::make('sort_order')->numeric()->default(0)])->columnSpanFull(), Repeater::make('documents')->relationship()->schema([TextInput::make('title')->required(),FileUpload::make('path')->disk('public')->directory('manuals')->acceptedFileTypes(['application/pdf'])->required(),TextInput::make('sort_order')->numeric()->default(0)])->columnSpanFull(), TextInput::make('meta_title')->columnSpanFull(),Textarea::make('meta_description')->columnSpanFull(),
            ]);
    }
}
