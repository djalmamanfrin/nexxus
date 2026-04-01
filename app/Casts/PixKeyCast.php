<?php

namespace App\Casts;

use App\Domain\PixKey\PixKeyFactory;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Stringable;

class PixKeyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (empty($value)) {
            return $value;
        }
        return PixKeyFactory::make($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value instanceof Stringable
            ? $value->value()
            : $value;
    }
}
