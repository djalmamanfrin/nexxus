<?php

namespace App\Observers;

use App\Models\Expense;
use App\Models\ExpenseStatus;

class ExpenseObserver
{
    public function creating(Expense $expense): void
    {
        $expense->expense_status_id = $this->shouldMarkAsUnreconciled($expense)
            ? ExpenseStatus::UNRECONCILED
            : ExpenseStatus::PENDING;
    }

    public function saving(Expense $expense): void
    {
        if ($this->shouldMarkAsUnreconciled($expense)) {
            $expense->expense_status_id = ExpenseStatus::UNRECONCILED;
        }
    }
    private function shouldMarkAsUnreconciled(Expense $expense): bool
    {
        return
            $expense->expense_status_id !== ExpenseStatus::UNRECONCILED &&
            !empty($expense->payee_id) &&
            !empty($expense->cost_center_id) &&
            $expense->amount->isPositive() &&
            $expense->due_at->hasValue();
    }
}
