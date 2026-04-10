<?php

namespace App\Domain\Payments;

use Illuminate\Support\Collection;

class PaymentLinkCollection extends Collection
{
    public static function fromArray(array $items): self
    {
        return new self(
            array_map(fn ($item) => PaymentLink::fromArray($item), $items)
        );
    }

    public function totalAmount(): float
    {
        return $this->sum(fn (PaymentLink $p) => $p->amount);
    }

    public function paymentIds(): array
    {
        return $this->map(fn (PaymentLink $p) => $p->paymentId)->toArray();
    }
}
