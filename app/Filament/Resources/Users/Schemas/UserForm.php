<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\{TextInput,Select,Toggle};
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('phone')->label('Telefone'),
                TextInput::make('tax_number')->label('NIF'),
                TextInput::make('company')->label('Empresa'),
                Select::make('customer_type')->label('Tipo')->options(['private'=>'Particular','professional'=>'Profissional','reseller'=>'Revendedor'])->default('private'),
                Toggle::make('is_admin')->label('Acesso à administração'),
                TextInput::make('password')
                    ->label('Palavra-passe')
                    ->password()
                    ->revealable()
                    ->confirmed()
                    ->required(fn ($livewire): bool => $livewire instanceof CreateRecord)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->minLength(10),
                TextInput::make('password_confirmation')
                    ->label('Confirmar palavra-passe')
                    ->password()
                    ->revealable()
                    ->required(fn ($livewire): bool => $livewire instanceof CreateRecord)
                    ->dehydrated(false),
            ]);
    }
}
