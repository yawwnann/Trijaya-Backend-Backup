<?php

namespace App\Filament\Resources\PengaturanTampilanResource\Pages;

use App\Filament\Resources\PengaturanTampilanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengaturanTampilans extends ListRecords
{
    protected static string $resource = PengaturanTampilanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
