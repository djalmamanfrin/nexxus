<?php

namespace App\Casts;

use App\Domain\VO\CurrencyValue;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AmountCast implements CastsAttributes
{
    public function get(Model $model, string $key, $value, array $attributes): CurrencyValue
    {
        return new CurrencyValue($value);
    }

    public function set(Model $model, string $key, $value, array $attributes): float|null
    {
        if ($value instanceof CurrencyValue) {
            return $value->value();
        }

        return empty($value) ? $value : (float) $value;
    }
}
