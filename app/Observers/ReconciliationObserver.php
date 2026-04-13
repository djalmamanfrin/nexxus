<?php

namespace App\Observers;

use App\Models\ExpenseStatus;
use App\Models\PaymentStatus;
use App\Models\Reconciliation;
use App\Support\Logger;

class ReconciliationObserver
{
    public function saved(Reconciliation $reconciliation): void
    {
        $expense = $reconciliation->expense;
        $payment = $reconciliation->payment;

        if (! $expense || ! $payment) {
            return;
        }

        $reconciliationTotal = $payment->reconciliation()->sum('amount');
        $paymentAmount = $payment->amount->value();
        $expenseAmount = $expense->amount->value();

        $expenseStatus = $this->resolveExpenseStatus($reconciliationTotal, $expenseAmount);
        $expense->expense_status_id = $expenseStatus;
        $expense->saveQuietly();

        $paymentStatus = $this->resolvePaymentStatus($reconciliationTotal, $paymentAmount);
        $payment->payment_status_id = $paymentStatus;
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
