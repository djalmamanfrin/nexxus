<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\PaymentStatus;

class PaymentObserver
{
    public function saving(Payment $payment): void
    {
        if ($this->shouldMarkAsPaid($payment)) {
            $payment->payment_status_id = PaymentStatus::DONE;
        }
    }
    private function shouldMarkAsPaid(Payment $payment): bool
    {
        return
            $payment->payment_status_id !== PaymentStatus::DONE &&
            !empty($payment->bank_account_id) &&
            !empty($payment->amount) &&
            !empty($payment->paid_at?->value());
    }
}
