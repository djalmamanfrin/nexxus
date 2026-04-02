<?php

namespace App\Domain;

abstract class AbstractValidator implements ValidatorInterface, ValidatorCastInterface
{
    protected string $value;
    private ValidatorType $type;

    public function __construct(ValidatorType $type, string $value)
    {
        $value = $this->sanitize($value);
        $this->validate($value);
        $this->value = $value;
        $this->type = $type;
    }
    public static abstract function matches(string $value): bool;
    public abstract function validate(string $value): void;
    public abstract function mask(): string;

    public function sanitize(string $value): string
    {
        return preg_replace('/\D/', '', $value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function type(): ValidatorType
    {
        return $this->type;
    }

    public function formatted(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
