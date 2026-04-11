<?php

namespace App\Http\Resources;

use App\Support\Format;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => [
                'value' => $this->amount,
                'formatted' => Format::money($this->amount),
            ],
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'end_to_end_id' => $this->end_to_end_id,
            'paid_at' => $this->paid_at,
            'created_at' => $this->created_at,
            'bank_account' => $this->whenLoaded('bankAccount', function () {
                return [
                    'id' => $this->bank_account_id,
                    'code' => $this->bankAccount?->name,
                ];
            }),
            'status' => $this->whenLoaded('status', function () {
                return [
                    'id' => $this->payment_status_id,
                    'name' => $this->status?->name,
                    'color' => $this->status?->color,
                ];
            }),
            'attachments' => $this->whenLoaded(
                'attachments',
                fn () => $this->attachments->map(fn ($file) => [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'url' => $file->url,
                ]),
                []
            ),
        ];
    }
}
