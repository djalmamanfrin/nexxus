<?php

namespace App\Domain\VO;

use Carbon\Carbon;
use JsonSerializable;

class DateValue implements JsonSerializable
{
    public function __construct(
        protected ?Carbon $date
    ) {}

    public function value(string $format = 'Y-m-d'): ?string
    {
        return $this->date?->format($format);
    }

    public function formatted(string $format = 'd/m/Y'): ?string
    {
        return $this->date?->format($format);
    }

    public function hasValue(): bool
    {
        return $this->date !== null;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value(),
            'formatted' => $this->formatted(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
