<?php

namespace App\Observers;

use App\Models\ExpenseStatus;
use App\Models\PaymentStatus;
use App\Models\Reconciliation;

class ReconciliationObserver
{
    public function saved(Reconciliation $reconciliation): void
    {
        $expense = $reconciliation->expense;
        $payment = $reconciliation->payment;

        if (! $expense || ! $payment) {
            return;
        }

        $expenseTotal = $expense->payments()->sum('reconciliation.amount');
        $expenseAmount = $expense->amount->value();
        $paymentTotal = $payment->expenses()->sum('reconciliation.amount');
        $paymentAmount = $payment->amount->value();

        $expense->expense_status_id = $this->resolveExpenseStatus($expenseTotal, $expenseAmount);
        $expense->saveQuietly();

        $payment->payment_status_id = $this->resolvePaymentStatus($paymentTotal, $paymentAmount);
        $payment->saveQuietly();
    }

    private function resolveExpenseStatus(int $linkedAmount, int $totalAmount): int
    {
        if ($linkedAmount === 0) {
            return ExpenseStatus::UNRECONCILED;
        }

        if ($linkedAmount < $totalAmount) {
            return ExpenseStatus::PARTIAL;
        }

        if ($linkedAmount === $totalAmount) {
            return ExpenseStatus::RECONCILED;
        }

        return ExpenseStatus::EXCESS;
    }
    private function resolvePaymentStatus(int $linkedAmount, int $totalAmount): int
    {
        if ($linkedAmount === 0) {
            return PaymentStatus::UNRECONCILED;
        }

        if ($linkedAmount < $totalAmount) {
            return PaymentStatus::PARTIAL;
        }

        if ($linkedAmount === $totalAmount) {
            return PaymentStatus::RECONCILED;
        }

        return PaymentStatus::EXCESS;
    }
}
