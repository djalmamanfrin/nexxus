<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Domain\Documents\DocumentFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Stringable;

class DocumentCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return DocumentFactory::make($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value instanceof Stringable
            ? $value->value()
            : $value;
    }
}
