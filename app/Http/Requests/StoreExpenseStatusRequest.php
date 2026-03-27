<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreExpenseStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->name) {
            $this->merge([
                'slug' => Str::slug($this->name),
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
                Rule::unique('expense_statuses', 'slug')
            ],

            'slug' => [
                'required',
                'string',
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors(),
            ], 422)
        );
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O status é obrigatório.',
            'name.string' => 'O status deve ser uma string.',
            'name.min' => 'O status deve ter pelo menos 3 caracteres.',
            'name.max' => 'O status deve ter no máximo 30 caracteres.',
            'name.unique' => 'O status já está em uso.',
            'slug.required' => 'O slug do status é obrigatório.',
            'slug.string' => 'O slug do status deve ser uma string.',
        ];
    }
}
