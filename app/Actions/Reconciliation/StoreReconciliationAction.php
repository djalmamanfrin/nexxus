<?php

namespace App\Actions\Reconciliation;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Reconciliation;
use InvalidArgumentException;

class StoreReconciliationAction
{
    public function execute(array $expenses, array $payments): void
    {
        if (empty($expenses)) {
            throw new InvalidArgumentException('Selecione ao menos uma despesa');
        }

        if (empty($payments)) {
            throw new InvalidArgumentException('Selecione ao menos um pagamento');
        }

        $expenseIds = collect($expenses)->pluck('id')->all();
        $paymentIds = collect($payments)->pluck('id')->all();

        $expenseModels = Expense::query()
            ->whereIn('id', $expenseIds)
            ->get();

        $paymentModels = Payment::query()
            ->whereIn('id', $paymentIds)
            ->get();

        if ($expenseModels->count() !== count($expenseIds)) {
            throw new InvalidArgumentException('Uma ou mais despesas informadas não foram encontradas');
        }

        if ($paymentModels->count() !== count($paymentIds)) {
            throw new InvalidArgumentException('Uma ou mais pagamentos informados não foram encontrados');
        }

        $totalExpenses = collect($expenses)->sum(fn (array $expense) => (int) $expense['amount']);
        $totalPayments = collect($payments)->sum(fn (array $payment) => (int) $payment['amount']);

        if (($totalExpenses - $totalPayments) !== 0) {
            throw new InvalidArgumentException('A conciliação só pode ser salva quando a soma das despesas e dos pagamentos for igual a zero');
        }

        foreach ($expenses as $expense) {
            foreach ($payments as $payment) {
                Reconciliation::updateOrCreate(
                    [
                        'expense_id' => $expense['id'],
                        'payment_id' => $payment['id'],
                    ],
                    [
                        'amount' => $payment['amount'],
                        'linked_at' => now(),
                    ]
                );
            }
        }
    }
}
