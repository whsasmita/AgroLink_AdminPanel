<?php

namespace App\Filament\Resources\Payouts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PayoutInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('transaction_id'),
                TextEntry::make('payee_id'),
                TextEntry::make('payee_type')
                    ->badge(),
                TextEntry::make('amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('released_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
