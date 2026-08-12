<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{FileUpload,RichEditor,TextInput,Textarea,Toggle};

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required(),TextInput::make('slug')->required()->unique(ignoreRecord:true),RichEditor::make('content')->columnSpanFull(),FileUpload::make('hero_image')->image()->disk('public')->directory('pages'),Toggle::make('is_published')->default(true),TextInput::make('meta_title')->columnSpanFull(),Textarea::make('meta_description')->columnSpanFull(),
            ]);
    }
}
