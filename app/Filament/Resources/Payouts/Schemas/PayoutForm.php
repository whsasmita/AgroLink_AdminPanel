<?php

namespace App\Filament\Resources\Payouts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayoutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('transaction_id')
                    ->required(),
                TextInput::make('payee_id')
                    ->required(),
                Select::make('payee_type')
                    ->options(['worker' => 'Worker', 'driver' => 'Driver'])
                    ->required(),
                TextInput::make('amount')
                    ->numeric(),
                Select::make('status')
                    ->options([
            'pending_disbursement' => 'Pending disbursement',
            'completed' => 'Completed',
            'failed' => 'Failed',
        ])
                    ->default('pending_disbursement'),
                DateTimePicker::make('released_at'),
            ]);
    }
}
