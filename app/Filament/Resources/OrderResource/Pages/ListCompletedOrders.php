<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListCompletedOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
    protected static ?string $navigationGroup = 'Manajemen Pesanan';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()?->where('status', 'completed');
    }

    public static function getNavigationLabel(): string
    {
        return 'Berhasil';
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return true;
    }
}