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
    private function shouldMarkAsUnreconciled(Payment $payment): bool
    {
        return
            $payment->payment_status_id !== PaymentStatus::UNRECONCILED &&
            !empty($payment->bank_account_id) &&
            !empty($payment->amount) &&
            !empty($payment->paid_at?->value());
    }
}
