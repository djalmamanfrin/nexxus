<?php

namespace App\Support;

class Format
{
    public static function money(?float $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}
