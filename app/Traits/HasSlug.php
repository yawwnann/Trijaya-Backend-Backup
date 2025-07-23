<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (empty($model->slug) && !empty($model->nama)) {
                $model->slug = Str::slug($model->nama);
            }
        });
        static::updating(function ($model) {
            if (empty($model->slug) && !empty($model->nama)) {
                $model->slug = Str::slug($model->nama);
            }
        });
    }
}