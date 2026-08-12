<?php

namespace App\Filament\Resources\QuoteRequests\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Select,TextInput,Textarea};

class QuoteRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')->relationship('product','name')->searchable(),TextInput::make('name')->required(),TextInput::make('email')->email()->required(),TextInput::make('phone'),TextInput::make('subject'),Textarea::make('message')->required()->columnSpanFull(),Select::make('status')->options(['new'=>'Novo','contacted'=>'Contactado','closed'=>'Fechado'])->required(),Textarea::make('internal_notes')->columnSpanFull(),
            ]);
    }
}
