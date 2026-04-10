<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->competence_date) {
            $this->merge([
                'competence_date' => $this->competence_date . '-01',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'reference' => ['nullable', 'string'],
            'payee_id' => ['nullable', 'integer'],
            'cost_center_id' => ['nullable', 'integer'],
            'amount' => ['nullable', 'numeric'],
            'due_at' => ['nullable', 'date'],
            'competence_date' => ['nullable', 'date'],
            'payments' => ['nullable', 'array'],
            'payments.*.id' => ['required', 'exists:payments,id'],
            'payments.*.amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $payments = collect($this->input('payments', []));
            $total = $payments->sum('amount');
            if ($this->amount && $total > $this->amount) {
                $validator->errors()->add(
                    'payments',
                    'Total dos pagamentos excede o valor da despesa.'
                );
            }
        });
    }
}
