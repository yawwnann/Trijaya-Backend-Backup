<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Order;

class PesananDiproses extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Manajemen Pesanan';
    protected static ?string $navigationLabel = 'Diproses';
    protected static ?string $title = 'Pesanan Diproses';
    protected static string $view = 'filament.pages.pesanan-diproses';

    protected function getTableQuery()
    {
        return Order::query()->where('status', 'processing');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('order_number')->label('Nomor Pesanan')->searchable(),
            Tables\Columns\TextColumn::make('user.name')->label('Pelanggan')->searchable(),
            Tables\Columns\TextColumn::make('grand_total')->label('Total')->money('IDR')->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn($state) => match ($state) {
                    'pending' => 'warning',
                    'processing' => 'primary', // Ganti 'orange' jadi 'primary'
                    'shipped' => 'info',
                    'delivered' => 'success',
                    'cancelled' => 'danger',
                    'completed' => 'success',
                    default => 'secondary',
                })
                ->formatStateUsing(fn(string $state): string => match ($state) {
                    'pending' => 'Menunggu',
                    'processing' => 'Diproses',
                    'shipped' => 'Dikirim',
                    'delivered' => 'Diterima',
                    'cancelled' => 'Dibatalkan',
                    'completed' => 'Selesai',
                    default => 'Status Tidak Dikenal',
                }),
            Tables\Columns\TextColumn::make('payment_status')
                ->label('Pembayaran')
                ->badge()
                ->color(fn($state) => match ($state) {
                    'pending' => 'warning',
                    'paid' => 'success',
                    'failed' => 'danger',
                    default => 'secondary',
                })
                ->formatStateUsing(fn(string $state): string => match ($state) {
                    'pending' => 'Menunggu',
                    'paid' => 'Lunas',
                    'failed' => 'Gagal',
                }),
            Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y ')->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ];
    }
}