<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $province_id
 * @property string $name
 */
class Regency extends Model
{
    protected $fillable = ['id', 'province_id', 'name'];
    public $timestamps = false;
    protected $casts = [
        'id' => 'string',
        'province_id' => 'string',
    ];
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}