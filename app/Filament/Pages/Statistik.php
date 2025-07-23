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

    public function getStats()
    {
        return [
            'total_users' => \App\Models\User::count(),
            'total_orders' => \App\Models\Order::count(),
            'masuk' => 0,
            'batal' => 0,
            'berhasil' => 0,
            'produk' => \App\Models\Produk::count(),
            'total_pemasukan' => 0,
        ];
    }

    public function getBestSellers()
    {
        // Contoh: ambil 5 produk terlaris berdasarkan jumlah order item
        return \App\Models\Produk::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(5)
            ->get();
    }

    public function getColumnSpan()
    {
        return 1;
    }

    public function getColumnStart()
    {
        return 1;
    }
}