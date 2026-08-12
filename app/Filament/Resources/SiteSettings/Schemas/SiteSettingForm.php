<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Select,TextInput,Textarea};

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')->required()->unique(ignoreRecord:true),TextInput::make('label')->required(),Select::make('type')->options(['text'=>'Texto','textarea'=>'Texto longo','email'=>'Email','phone'=>'Telefone'])->default('text'),Textarea::make('value')->columnSpanFull(),
            ]);
    }
}
