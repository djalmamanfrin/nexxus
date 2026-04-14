<?php

namespace App\Actions\Reconciliation;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Reconciliation;
use Illuminate\Validation\ValidationException;

class StoreReconciliationAction
{
    public function execute(array $expenses, array $payments): void
    {
        if (empty($expenses)) {
            throw ValidationException::withMessages([
                'expense_ids' => 'Selecione ao menos uma despesa.',
            ]);
        }

        if (empty($payments)) {
            throw ValidationException::withMessages([
                'payments' => 'Selecione ao menos um pagamento.',
            ]);
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
            throw ValidationException::withMessages([
                'expenses' => 'Uma ou mais despesas informadas não foram encontradas.',
            ]);
        }

        if ($paymentModels->count() !== count($paymentIds)) {
            throw ValidationException::withMessages([
                'payments' => 'Um ou mais pagamentos informados não foram encontrados.',
            ]);
        }

        $totalExpenses = collect($expenses)->sum(fn (array $expense) => (int) $expense['amount']);
        $totalPayments = collect($payments)->sum(fn (array $payment) => (int) $payment['amount']);

        if (($totalExpenses - $totalPayments) !== 0) {
            throw ValidationException::withMessages([
                'payments' => 'A conciliação só pode ser salva quando a soma das despesas e dos pagamentos for igual a zero.',
            ]);
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
