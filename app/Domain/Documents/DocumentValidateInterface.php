<?php

namespace App\Domain\Documents;

interface DocumentValidateInterface
{
    public static function matches(string $value): bool;
    public function validate(string $value): void;
}
