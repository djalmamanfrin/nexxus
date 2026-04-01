<?php

namespace App\Domain\PixKey;

use App\Enums\PixKeyType;

abstract class AbstractPixKey implements PixKeyInterface
{

    protected string $value;
    private PixKeyType $type;

    public function __construct(PixKeyType $type, string $value)
    {
        $value = $this->sanitize($value);
        $this->isValid($value);
        $this->value = $value;
    }
    protected abstract function isValid(string $value): void;
    public static abstract function matches(string $value): bool;

    protected function sanitize(string $value): string
    {
        return strtolower(trim($value));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function type(): PixKeyType
    {
        return $this->type;
    }

    public function formatted(): string
    {
        return $this->value;
    }

    public function mask(): ?string
    {
        return null;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
