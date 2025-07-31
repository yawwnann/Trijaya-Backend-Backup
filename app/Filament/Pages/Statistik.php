<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\StatistikOverview;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\CustomersChart;

class Statistik extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Statistik';
    protected static ?string $slug = 'statistik';
    protected static string $view = 'filament.pages.statistik';

    protected function getHeaderWidgets(): array
    {
        return [
            StatistikOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            OrdersChart::class,
            CustomersChart::class,
        ];
    }
}