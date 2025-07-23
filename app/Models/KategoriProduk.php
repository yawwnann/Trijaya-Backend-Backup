<?php

namespace App\Models;

use App\Traits\HasSlug; // <-- 1. Import Trait
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriProduk extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['nama', 'slug', 'deskripsi'];

    // Panggil metode boot dari trait
    protected static function boot()
    {
        parent::boot();
        static::bootHasSlug();
    }

    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class);
    }
}