<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $label
 * @property string $recipient_name
 * @property string $phone
 * @property string $address
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $postal_code
 * @property bool $is_default
 * @property string|null $notes
 * @property string|null $regency_id
 * @property string $full_address
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Address extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'address',
        'province',
        'city',
        'district',
        'postal_code',
        'is_default',
        'notes',
        'regency_id',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getFullAddressAttribute()
    {
        return "{$this->recipient_name} - {$this->address}, {$this->city}, {$this->province}";
    }
}