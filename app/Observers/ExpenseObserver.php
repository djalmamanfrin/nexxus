<?php

namespace App\Observers;

use App\Models\Expense;
use App\Models\ExpenseStatus;

class ExpenseObserver
{
    public function saving(Expense $expense): void
    {
        if ($this->shouldMarkAsPaid($expense)) {
            $expense->expense_status_id = ExpenseStatus::DONE;
        }
    }
    private function shouldMarkAsPaid(Expense $expense): bool
    {
        return
            $expense->expense_status_id !== ExpenseStatus::DONE &&
            !empty($expense->payee_id) &&
            !empty($expense->cost_center_id) &&
            !empty($expense->amount) &&
            !empty($expense->due_at);
    }
}
