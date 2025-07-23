<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 */
class Province extends Model
{
    protected $fillable = ['id', 'name'];
    public $timestamps = false;
    protected $casts = [
        'id' => 'string',
    ];
    public function regencies(): HasMany
    {
        return $this->hasMany(Regency::class);
    }
}