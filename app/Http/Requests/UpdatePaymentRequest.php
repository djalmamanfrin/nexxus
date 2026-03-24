<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('amount')) {
            $this->merge([
                'amount' => $this->amount / 100,
            ]);
        }
    }


    public function rules(): array
    {
        return [
            'expense_id' => ['nullable', 'integer'],
            'bank_account_id' => ['nullable', 'integer'],
            'payment_status_id' => ['nullable', 'integer'],
            'payment_type_id' => ['nullable', 'integer'],
            'amount' => ['nullable', 'numeric'],
            'paid_at' => ['nullable', 'date'],
        ];
    }
}
