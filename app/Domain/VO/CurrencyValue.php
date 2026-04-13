<?php

namespace App\Domain\VO;

use JsonSerializable;

class CurrencyValue implements JsonSerializable
{
    protected float $value;

    public function __construct(float|int|string|null $value = null)
    {
        $this->value = $this->normalize($value);
    }

    protected function normalize($value): int
    {
        if (empty($value)) {
            return 0;
        }

        return (int) $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function formatted(): string
    {
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }

    public function isPositive(): bool
    {
        return $this->value > 0;
    }

    public function isZero(): bool
    {
        return $this->value == 0;
    }

    public function add(self $other): self
    {
        return new self($this->value + $other->value());
    }

    public function subtract(self $other): self
    {
        return new self($this->value - $other->value());
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
