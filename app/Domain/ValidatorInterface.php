<?php

namespace App\Domain;

interface ValidatorInterface
{
    public static function matches(string $value): bool;
    public function validate(string $value): void;
}
