<?php

namespace App\Filament\Resources\PengaturanTampilanResource\Pages;

use App\Filament\Resources\PengaturanTampilanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Helpers\GumletUploader;

class EditPengaturanTampilan extends EditRecord
{
    protected static string $resource = PengaturanTampilanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['gambar_banner']) && $data['gambar_banner'] instanceof \Illuminate\Http\UploadedFile) {
            $file = $data['gambar_banner'];
            $url = GumletUploader::upload($file, 'banner');
            // Hapus file lokal setelah upload ke Gumlet
            if (file_exists($file->getPathname())) {
                @unlink($file->getPathname());
            }
            $data['gambar_banner'] = $url;
        }
        return $data;
    }
}
