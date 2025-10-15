<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
                TextInput::make('phone_number')
                    ->tel(),
                Select::make('role')
                    ->options([
            'farmer' => 'Farmer',
            'worker' => 'Worker',
            'driver' => 'Driver',
            'admin' => 'Admin',
            'general' => 'General',
        ])
                    ->required(),
                Textarea::make('profile_picture')
                    ->columnSpanFull(),
                Toggle::make('is_active'),
            ]);
    }
}
