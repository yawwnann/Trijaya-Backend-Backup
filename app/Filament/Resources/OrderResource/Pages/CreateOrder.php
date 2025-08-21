<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Pastikan key yang diperlukan ada
        $paymentStatus = $data['payment_status'] ?? null;

        // Auto-update: Jika payment_status = failed, status otomatis menjadi cancelled
        if ($paymentStatus === 'failed') {
            $data['status'] = 'cancelled';
        }

        return $data;
    }
}