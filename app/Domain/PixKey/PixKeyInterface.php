<?php

namespace App\Domain\PixKey;

use App\Enums\PixKeyType;

interface PixKeyInterface
{
    public static function matches(string $value): bool;
    public function value(): string;
    public function formatted(): string;
    public function type(): PixKeyType;
    public function mask(): ?string;
}
