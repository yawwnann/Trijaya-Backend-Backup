<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke produk dihapus karena foreign key constraint sudah dihapus
    // Data produk disimpan sebagai snapshot di product_name dan price
    // public function produk(): BelongsTo
    // {
    //     return $this->belongsTo(Produk::class, 'product_id');
    // }
}