<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{FileUpload,Select,TextInput,Textarea,Toggle};

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')->relationship('parent','name')->searchable()->preload(),TextInput::make('name')->required(),TextInput::make('slug')->required()->unique(ignoreRecord:true),Textarea::make('description')->columnSpanFull(),FileUpload::make('image')->image()->disk('public')->directory('categories'),TextInput::make('sort_order')->numeric()->default(0),Toggle::make('is_published')->default(true),TextInput::make('meta_title')->columnSpanFull(),Textarea::make('meta_description')->columnSpanFull(),
            ]);
    }
}
