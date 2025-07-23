<?php
namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Order;

class OrdersChart extends LineChartWidget
{
    protected static ?string $heading = 'Pemasukan per Bulan';

    protected function getData(): array
    {
        $data = Order::selectRaw('MONTH(created_at) as month, SUM(grand_total) as total')
            ->whereYear('created_at', now()->year)
            ->where('status', 'delivered')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $labels = [];
        $values = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date('M', mktime(0, 0, 0, $i, 10));
            $values[] = (float) ($data[$i] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }
}