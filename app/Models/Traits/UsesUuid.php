<?php

namespace App\Models\Traits;

use Facades\Str;

trait UsesUuid
{
    public static function bootUuidTrait()
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}