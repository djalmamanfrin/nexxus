<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['nullable', 'string'],
            'payee_id' => ['nullable', 'integer'],
            'cost_center_id' => ['nullable', 'integer'],
            'expense_status_id' => ['nullable', 'integer'],
            'payment_type_id' => ['nullable', 'integer'],
            'amount' => ['nullable', 'numeric'],
            'due_at' => ['nullable', 'date'],
            'competence_date' => ['nullable', 'date'],
        ];
    }
}
