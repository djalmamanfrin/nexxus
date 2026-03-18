<?php

namespace App\Actions\Attachment;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class StorePaymentAction
{
    public function __construct(
        protected AttachFileAction $attachFile
    ) {}

    /**
     * @throws \Throwable
     */
    public function execute(array $data): Payment
    {
        return DB::transaction(function () use ($data) {

            $payment = Payment::create();
            if (!empty($data['attachment'])) {
                $this->attachFile->execute(
                    $payment,
                    $data['attachment'],
                    'attachments/payments'
                );
            }

            return $payment;
        });
    }
}
