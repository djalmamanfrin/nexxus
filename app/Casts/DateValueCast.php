<?php

namespace App\Casts;

use App\Domain\VO\DateValue;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DateValueCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): DateValue
    {
        $date = $value ? Carbon::parse($value) : null;
        return new DateValue($date);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value instanceof DateValue
            ? $value->value()
            : $value;
    }
}
