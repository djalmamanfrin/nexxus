<?php

namespace App\Actions\Reconciliation;

use App\Models\Expense;
use App\Models\ExpenseStatus;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\Reconciliation;
use App\Support\Logger;
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

        $selectedExpensesTotal = collect($expenses)->sum(fn (array $expense) => (int) $expense['amount']);
        $selectedPaymentsTotal = collect($payments)->sum(fn (array $payment) => (int) $payment['amount']);

        $partialExpenseIds = $expenseModels
            ->filter(fn (Expense $expense) => $expense->expense_status_id === ExpenseStatus::PARTIAL)
            ->pluck('id')
            ->all();

        $alreadyReconciledTotal = Reconciliation::query()
            ->whereIn('expense_id', $partialExpenseIds)
            ->sum('amount');

        $difference = $alreadyReconciledTotal + $selectedPaymentsTotal - $selectedExpensesTotal;

        if ($difference !== 0) {
            Logger::error('StoreReconciliationAction', [
                'alreadyReconciledTotal' => $alreadyReconciledTotal,
                'selectedPaymentsTotal' => $selectedPaymentsTotal,
                'selectedExpensesTotal' => $selectedExpensesTotal,
            ]);
            throw new InvalidArgumentException(
                'A conciliação só pode ser salva quando a soma das despesas e dos pagamentos resultar em zero.'
            );
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
        Payment::whereIn('id', $paymentIds)
            ->update(['payment_status_id' => PaymentStatus::RECONCILED]);
        Expense::whereIn('id', $expenseIds)
            ->update(['expense_status_id' => ExpenseStatus::RECONCILED]);
    }
}
