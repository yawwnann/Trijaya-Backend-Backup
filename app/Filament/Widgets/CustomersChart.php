<?php
namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\OrderItem;

class CustomersChart extends BarChartWidget
{
    protected static ?string $heading = 'Top 5 Produk Terlaris';

    protected function getData(): array
    {
        $topItems = OrderItem::selectRaw('product_name, SUM(quantity) as total')
            ->groupBy('product_name')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'product_name')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Terjual',
                    'data' => array_values($topItems),
                ],
            ],
            'labels' => array_keys($topItems),
        ];
    }
}