<?php

namespace App\Domain\PixKey;

use App\Domain\Documents\CPF;

final class PixCpf extends AbstractPixKey
{
    private CPF $cpf;
    public function __construct(string $value)
    {
        $this->cpf = new CPF($value);
        parent::__construct(PixKeyType::PHONE, $value);
    }
    public static function matches(string $value): bool
    {
        return strlen($value) === 13;
    }
    public function formatted(): string
    {
        return $this->cpf->formatted();
    }
    public function mask(): string
    {
        return $this->cpf->mask();
    }
    protected function sanitize(string $value): string
    {
        return $this->cpf->sanitize($value);
    }

    protected function isValid(string $value): void
    {
        $this->cpf->validate($value);
    }
}
