<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invoice_id')
                    ->required(),
                TextInput::make('amount_paid')
                    ->numeric(),
                TextInput::make('payment_method'),
                DateTimePicker::make('transaction_date'),
            ]);
    }
}
