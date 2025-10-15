<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\User;
use App\Models\Transaction;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
       return [
    Stat::make('Users', User::count())
        ->description('All registered users')
        ->descriptionIcon('heroicon-m-users')
        ->color('success'),
        
    Stat::make('Transaction', Transaction::count())
        ->description('Total transactions')
        ->descriptionIcon('heroicon-m-credit-card')
        ->color('info'),
        
    Stat::make('Payout', '567')
        ->description('Payouts needed')
        ->descriptionIcon('heroicon-m-banknotes')
        ->color('warning'),
];
    }
}
