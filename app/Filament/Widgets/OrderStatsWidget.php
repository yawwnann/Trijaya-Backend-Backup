<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Produk;
use App\Models\OrderItem;
use Filament\Widgets\Widget;

class OrderStatsWidget extends Widget
{
    protected static string $view = 'filament.widgets.order-stats-widget';

    public function getStats()
    {
        return [
            'masuk' => Order::where('status', 'pending')->count(),
            'batal' => Order::where('status', 'cancelled')->count(),
            'berhasil' => Order::where('status', 'delivered')->count(),
            'produk' => Produk::count(),
            'total_pemasukan' => Order::where('status', 'delivered')->sum('grand_total'),
        ];
    }

    public function getBestSellers()
    {
        return OrderItem::selectRaw('product_name, SUM(quantity) as total')
            ->whereHas('order', function ($q) {
                $q->where('status', 'delivered');
            })
            ->groupBy('product_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    public function getChartData()
    {
        $bestSellers = $this->getBestSellers();

        return [
            'labels' => $bestSellers->pluck('product_name')->toArray(),
            'data' => $bestSellers->pluck('total')->toArray(),
        ];
    }
}