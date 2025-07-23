<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListProcessingOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
    protected static ?string $navigationGroup = 'Manajemen Pesanan';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()?->where('status', 'processing');
    }

    public static function getNavigationLabel(): string
    {
        return 'Diproses';
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return true;
    }
}