<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\KategoriProdukResource;

class ProdukResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'kategori_produk' => new KategoriProdukResource($this->whenLoaded('kategoriProduk')),
            'nama' => $this->resource->nama,
            'slug' => $this->resource->slug,
            'deskripsi' => $this->resource->deskripsi,
            'harga' => (float) $this->resource->harga,
            'berat' => (float) $this->resource->berat,
            'stok' => (int) $this->resource->stok,
            'gambar' => $this->resource->gambar,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}