<?php

namespace App\Filament\Resources\Payouts;

use App\Filament\Resources\Payouts\Pages\CreatePayout;
use App\Filament\Resources\Payouts\Pages\EditPayout;
use App\Filament\Resources\Payouts\Pages\ListPayouts;
use App\Filament\Resources\Payouts\Pages\ViewPayout;
use App\Filament\Resources\Payouts\Schemas\PayoutForm;
use App\Filament\Resources\Payouts\Schemas\PayoutInfolist;
use App\Filament\Resources\Payouts\Tables\PayoutsTable;
use App\Models\Payout;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PayoutResource extends Resource
{
    protected static ?string $model = Payout::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'payee_type';

    public static function form(Schema $schema): Schema
    {
        return PayoutForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PayoutInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayoutsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayouts::route('/'),
            'view' => ViewPayout::route('/{record}'),
        ];
    }
}
