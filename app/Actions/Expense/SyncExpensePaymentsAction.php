<?php

namespace App\Actions\Expense;

use App\Domain\Payments\PaymentLink;
use App\Domain\Payments\PaymentLinkCollection;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Validation\ValidationException;

class SyncExpensePaymentsAction
{
    public function execute(Expense $expense, array $payments): void
    {
        if (empty($payments)) {
            throw ValidationException::withMessages(['payments' => 'Pagamentos vazio']);
        }

        $paymentsCollection = PaymentLinkCollection::fromArray($payments);

        if ($paymentsCollection->totalAmount()->isZero()) {
            throw ValidationException::withMessages(['payments' => 'Valor total dos pagamentos deve ser maior que zero']);
        }

        if ($paymentsCollection->totalAmount()->value() > $expense->amount) {
            throw ValidationException::withMessages(['payments' => 'Pagamentos excedem o valor da despesa']);
        }

        $validPaymentIds = Payment::select('id')
            ->whereIn('id', $paymentsCollection->pluck('id'))
            ->get();

        if ($validPaymentIds->count() !== $paymentsCollection->count()) {
            throw ValidationException::withMessages(['payments' => 'Pagamentos informados não encontrados na base']);
        }

        $expense->payments()->sync($validPaymentIds->toArray());
    }
}
