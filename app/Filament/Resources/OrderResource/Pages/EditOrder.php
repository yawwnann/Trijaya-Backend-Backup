<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Pastikan key yang diperlukan ada
        $status = $data['status'] ?? null;
        $paymentStatus = $data['payment_status'] ?? null;
        $resi = $data['resi'] ?? null;
        $shippingCourier = $data['shipping_courier'] ?? null;

        // Validasi: Jika status = shipped, resi dan shipping_courier wajib diisi
        if ($status === 'shipped') {
            if (empty($resi) || empty($shippingCourier)) {
                throw ValidationException::withMessages([
                    'resi' => 'Nomor resi wajib diisi ketika status pesanan diubah menjadi "Dikirim"',
                    'shipping_courier' => 'Kurir pengiriman wajib diisi ketika status pesanan diubah menjadi "Dikirim"',
                ]);
            }
        }

        // Validasi: Status tidak bisa diubah jika payment_status = failed
        if ($paymentStatus === 'failed' && $status !== 'cancelled') {
            throw ValidationException::withMessages([
                'status' => 'Status pesanan tidak dapat diubah karena pembayaran gagal. Status otomatis menjadi "Dibatalkan"',
            ]);
        }

        // Auto-update: Jika payment_status = failed, status otomatis menjadi cancelled
        if ($paymentStatus === 'failed') {
            $data['status'] = 'cancelled';
        }

        return $data;
    }
}