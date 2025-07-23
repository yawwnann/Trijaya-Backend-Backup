<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class EditPesanan extends EditRecord
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var \App\Models\Pesanan $pesananRecord */
        $pesananRecord = $this->getRecord();
        $pesananRecord->loadMissing('items');

        $itemsDataFormatted = [];
        if ($pesananRecord->relationLoaded('items') && $pesananRecord->items->isNotEmpty()) {
            foreach ($pesananRecord->items as $ikanDalamPesanan) {
                $pivotData = $ikanDalamPesanan->pivot;
                if ($pivotData) {
                    $itemsDataFormatted[] = [
                        'ikan_id' => $ikanDalamPesanan->id,
                        'jumlah' => $pivotData->jumlah,
                        'harga_saat_pesan' => $pivotData->harga_saat_pesan,
                    ];
                }
            }
        }
        $data['items'] = $itemsDataFormatted;

        $total = 0;
        foreach ($itemsDataFormatted as $item) {
            $jumlah = $item['jumlah'] ?? 0;
            $harga = $item['harga_saat_pesan'] ?? 0;
            if (is_numeric($jumlah) && is_numeric($harga)) {
                $total += $jumlah * $harga;
            }
        }
        $data['total_harga'] = $total;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            /** @var \App\Models\Pesanan $record */
            $itemsDataFromForm = $data['items'] ?? [];
            $pesananDataToUpdate = Arr::except($data, ['items']);

            $calculatedTotal = 0;
            $pivotDataForSync = [];
            if (is_array($itemsDataFromForm)) {
                foreach ($itemsDataFromForm as $item) {
                    $ikanId = $item['ikan_id'] ?? null;
                    $jumlah = $item['jumlah'] ?? 0;
                    $harga = $item['harga_saat_pesan'] ?? 0;

                    if ($ikanId && is_numeric($jumlah) && $jumlah > 0 && is_numeric($harga)) {
                        $calculatedTotal += $jumlah * $harga;
                        $pivotDataForSync[$ikanId] = [
                            'jumlah' => $jumlah,
                            'harga_saat_pesan' => $harga,
                        ];
                    }
                }
            }
            $pesananDataToUpdate['total_harga'] = $calculatedTotal;

            $record->fill($pesananDataToUpdate);
            $record->save();

            if (is_array($itemsDataFromForm)) {
                $record->items()->sync($pivotDataForSync);
            }

            $record->refresh()->load('items');

            Notification::make()
                ->title('Pesanan berhasil diperbarui')
                ->success()
                ->send();

            return $record;
        });
    }
}