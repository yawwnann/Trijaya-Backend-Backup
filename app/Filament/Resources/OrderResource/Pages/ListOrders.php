<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Resources\Components\Tab;
use Filament\Actions;
use Filament\Tables\Filters\SelectFilter;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->label('Status Pesanan')
                ->options([
                    'processing' => 'Diproses',
                    'shipped' => 'Dikirim',
                    'completed' => 'Berhasil',
                    'all' => 'Semua',
                ])
                ->default('all')
                ->query(function ($query, $value) {
                    if ($value !== 'all') {
                        $query->where('status', $value);
                    }
                }),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Semua Pesanan';
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return true;
    }
}