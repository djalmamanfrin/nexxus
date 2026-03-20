<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StorePaymentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->name) {
            $this->merge([
                'code' => Str::slug($this->name),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                function ($attribute, $value, $fail) {
                    if (str_word_count($value) > 3) {
                        $fail('O status deve ter no máximo 3 palavras.');
                    }
                },
            ],

            'code' => [
                'required',
                'string',
                Rule::unique('payment_statuses', 'code'),
            ],
        ];
    }
}
