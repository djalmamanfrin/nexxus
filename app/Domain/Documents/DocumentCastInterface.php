<?php

namespace App\Domain\Documents;

interface DocumentCastInterface
{    public function value(): string;
    public function formatted(): string;
    public function type(): DocumentType;
    public function mask(): string;
}
