<?php

namespace App\Domain\Documents;

abstract class AbstractDocument implements DocumentValidateInterface, DocumentCastInterface
{
    protected string $value;
    private DocumentType $type;

    public function __construct(DocumentType $type, string $value)
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

    public function type(): DocumentType
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
