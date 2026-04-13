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
    private function shouldMarkAsUnreconciled(Expense $expense): bool
    {
        return
            $expense->expense_status_id !== ExpenseStatus::UNRECONCILED &&
            !empty($expense->payee_id) &&
            !empty($expense->cost_center_id) &&
            !empty($expense->amount) &&
            !empty($expense->due_at);
    }
}
