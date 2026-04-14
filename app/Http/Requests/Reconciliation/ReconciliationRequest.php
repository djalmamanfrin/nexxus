<?php

namespace App\Http\Requests\Reconciliation;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReconciliationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expenses' => ['required', 'array', 'min:1'],
            'expenses.*.id' => ['required', 'integer', 'exists:expenses,id'],
            'expenses.*.amount' => ['required', 'integer', 'min:1', 'max:9999999'],
            'payments' => ['required', 'array', 'min:1'],
            'payments.*.id' => ['required', 'integer', 'exists:payments,id'],
            'payments.*.amount' => ['required', 'integer', 'min:1', 'max:9999999'],
        ];
    }
}
