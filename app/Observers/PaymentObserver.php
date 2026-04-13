<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\PaymentStatus;

class PaymentObserver
{
    public function creating(Payment $payment): void
    {
        $payment->payment_status_id = $this->shouldMarkAsUnreconciled($payment)
            ? PaymentStatus::UNRECONCILED
            : PaymentStatus::PENDING;
    }

    public function saving(Payment $payment): void
    {
        if ($this->shouldMarkAsUnreconciled($payment)) {
            $payment->payment_status_id = PaymentStatus::UNRECONCILED;
        }
    }
    private function shouldMarkAsUnreconciled(Payment $payment): bool
    {
        return
            $payment->payment_status_id !== PaymentStatus::UNRECONCILED &&
            !empty($payment->bank_account_id) &&
            $payment->amount->isPositive() &&
            $payment->paid_at->hasValue();
    }
}
