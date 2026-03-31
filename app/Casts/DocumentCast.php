<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Domain\Documents\DocumentFactory;

class DocumentCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return DocumentFactory::make($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value instanceof \Stringable
            ? $value->value()
            : preg_replace('/\D/', '', $value);
    }
}
