<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UploadTestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'file' => $this->resource->file,
            'gambar_utama' => $this->resource->gambar_utama,
            'file_url' => $this->resource->file_url,
            'gambar_utama_url' => $this->resource->gambar_utama_url,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}