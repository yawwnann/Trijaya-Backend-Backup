<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class District extends Model
{
    protected $fillable = ['id', 'regency_id', 'name'];
    public $timestamps = false;
    protected $casts = [
        'id' => 'string',
        'regency_id' => 'string',
    ];
    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class);
    }
}