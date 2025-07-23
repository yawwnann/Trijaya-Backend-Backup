<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KategoriProdukResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'nama' => $this->resource->nama,
            'slug' => $this->resource->slug,
            'deskripsi' => $this->resource->deskripsi,
            'produks' => ProdukResource::collection($this->whenLoaded('produks')),
        ];
    }
}