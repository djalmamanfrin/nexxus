<?php

namespace App\Domain\Payments;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

readonly class PaymentLink
{
    public int $paymentId;
    public float $amount;

    public function __construct(int $paymentId, float $amount)
    {
        if ($paymentId <= 0) {
            throw ValidationException::withMessages(['payments' => 'ID do pagamento inválido']);
        }

        if ($amount <= 0) {
            throw ValidationException::withMessages(['payments' => 'Valor deve ser maior que zero']);
        }

        $this->paymentId = $paymentId;
        $this->amount = $amount;
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['id'])) {
            throw new InvalidArgumentException('Id do pagamento é obrigatório');
        }

        if (!isset($data['amount'])) {
            throw new InvalidArgumentException("Valor do pagamento Id {$data['id']} é obrigatório");
        }

        return new self(
            (int) $data['id'],
            (float) $data['amount']
        );
    }

    public function toArray(): array
    {
        return [
            $this->paymentId => [
                'amount' => $this->amount,
                'linked_at' => now(),
            ],
        ];
    }
}
