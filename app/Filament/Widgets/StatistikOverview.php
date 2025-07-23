<?php
namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\Produk;

class StatistikOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPemasukan = Order::where('status', 'completed')->sum('grand_total');
        $pesananBerhasil = Order::where('status', 'completed')->count();
        $pesananGagal = Order::where('status', 'cancelled')->count();
        $pesananProses = Order::whereIn('status', ['processing', 'shipped'])->count();
        $pesananBaru = Order::where('status', 'pending')->count();
        $jumlahBarang = Produk::count();

        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalPemasukan, 0, ',', '.'))
                ->description('Total pemasukan dari pesanan berhasil')
                ->color('success'),
            Stat::make('Pesanan Berhasil', $pesananBerhasil)
                ->description('Pesanan dengan status completed')
                ->color('success'),
            Stat::make('Pesanan Gagal', $pesananGagal)
                ->description('Pesanan dengan status cancelled')
                ->color('danger'),
            Stat::make('Pesanan Proses', $pesananProses)
                ->description('Pesanan dengan status processing/shipped')
                ->color('warning'),
            Stat::make('Pesanan Baru Masuk', $pesananBaru)
                ->description('Pesanan dengan status pending')
                ->color('info'),
            Stat::make('Jumlah Barang', $jumlahBarang)
                ->description('Total produk yang tersedia')
                ->color('primary'),
        ];
    }
}