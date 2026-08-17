<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required()->live(onBlur: true)->afterStateUpdated(fn (Set $set, Get $get, ?string $state) => blank($get('slug')) ? $set('slug', Str::slug($state ?? '')) : null), TextInput::make('slug')->required()->unique(ignoreRecord: true)->helperText('Gerado automaticamente; pode ser editado.'), RichEditor::make('content')->columnSpanFull(), FileUpload::make('hero_image')->image()->disk('public')->directory('pages')->maxSize(12288)->rules(['dimensions:min_width=1600'])->imageEditor()->imagePreviewHeight('240')->helperText('Para uma faixa editorial use uma imagem horizontal com pelo menos 1600 px de largura.'), Toggle::make('is_published')->default(true), TextInput::make('meta_title')->columnSpanFull(), Textarea::make('meta_description')->columnSpanFull(),
            ]);
    }
}
