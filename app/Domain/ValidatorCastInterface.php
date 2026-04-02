<?php

namespace App\Domain;

interface ValidatorCastInterface
{    public function value(): string;
    public function formatted(): string;
    public function type(): ValidatorType;
    public function mask(): string;
}
