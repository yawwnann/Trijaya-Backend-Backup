<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'total_amount',
        'shipping_cost',
        'shipping_service',
        'shipping_courier',
        'tax',
        'discount',
        'grand_total',
        'status',
        'payment_status',
        'payment_method',
        'payment_token',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function setPaymentStatusAttribute($value)
    {
        $this->attributes['payment_status'] = $value;
        if ($value === 'failed') {
            $this->attributes['status'] = 'cancelled';
        }
    }
}