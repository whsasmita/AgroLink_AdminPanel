<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Payout;

class StatsOverview extends StatsOverviewWidget
{
    // Place this after the default Filament cards (Welcome, Filament Info)
    protected static ?int $sort = 100;
    protected function getStats(): array
    {
       // Net profit: 5% of amount + 5000 minus Midtrans fee by payment method
       $net = DB::table('transactions')
           ->selectRaw("SUM(((amount_paid * 0.05) + 5000) - (CASE 
               WHEN LOWER(payment_method) LIKE '%virtual account%' THEN 4000
               WHEN LOWER(payment_method) LIKE '%qris%' THEN amount_paid * 0.007
               WHEN LOWER(payment_method) LIKE '%gopay%' OR LOWER(payment_method) LIKE '%shopee%' THEN amount_paid * 0.02
               WHEN LOWER(payment_method) LIKE '%dana%' THEN amount_paid * 0.015
               ELSE 0 END)) as net")
           ->value('net') ?? 0;

       return [
    Stat::make('Users', User::count())
        ->description('All registered users')
        ->descriptionIcon('heroicon-m-users')
        ->color('success'),
        
    Stat::make('Transaction', Transaction::count())
        ->description('Total transactions')
        ->descriptionIcon('heroicon-m-credit-card')
        ->color('info'),
        
    Stat::make('Payout', Payout::where('status', 'pending_disbursement')->count())
        ->description('Payouts needed')
        ->descriptionIcon('heroicon-m-banknotes')
        ->color('warning'),

    Stat::make('Net Profit', 'Rp ' . number_format((float) $net, 0, ',', '.'))
        ->description('Keuntungan bersih platform')
        ->descriptionIcon('heroicon-m-currency-dollar')
        ->color('success'),
];
    }
}
