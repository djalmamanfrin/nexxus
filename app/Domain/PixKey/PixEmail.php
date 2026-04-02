<?php

namespace App\Domain\PixKey;

use App\Domain\AbstractValidator;
use App\Domain\ValidatorType;
use Illuminate\Validation\ValidationException;

final class PixEmail extends AbstractValidator
{
    public function __construct(string $value)
    {
        parent::__construct(ValidatorType::EMAIL, $value);
    }
    public function mask(): string
    {
        return '';
    }
    protected function sanitize(string $value): string
    {
        return strtolower(trim($value));
    }
    public static function matches(string $value): bool
    {
        return !empty($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
    public function validate(string $value): void
    {
        if (empty($value)) {
            throw ValidationException::withMessages(['pix_key' => 'Email vazio']);
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages(['pix_key' => 'Email inválido']);
        }

        if (strlen($value) > 254) {
            throw ValidationException::withMessages(['pix_key' => 'Email muito grande fora do padrão RFC']);
        }

        $domain = substr(strrchr($value, "@"), 1);
        if (!$domain || !str_contains($domain, '.')) {
            throw ValidationException::withMessages(['pix_key' => 'Email com domínio inválido']);
        }
    }
}
