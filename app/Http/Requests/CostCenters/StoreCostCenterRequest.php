<?php

namespace App\Http\Requests\CostCenters;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreCostCenterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'work_id' => ['required', 'integer', 'exists:works,id'],
            'cost_center_type_id' => ['required', 'integer', 'exists:cost_center_types,id'],
            'code' => [
                'required',
                'string',
                'min:3',
                'max:12',
                function ($attribute, $value, $fail) {
                    if (str_word_count($value) > 1) {
                        $fail('O status não deve conter espaços.');
                    }
                },
                Rule::unique('cost_centers', 'code')
            ],
            'budget' => [
                'required',
                'numeric',
                'min:0.01',
                'max:99999.99'
            ],
            'description' => [
                'nullable',
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
            'work_id.required' => 'A obra é obrigatório.',
            'work_id.numeric' => 'A obra deve ser um número válido.',
            'work_id.exists' => 'Obra não encontrado na base.',
            'cost_center_type_id.required' => 'O tipo centro de custo é obrigatório.',
            'cost_center_type_id.numeric' => 'O tipo centro de custo deve ser um número válido.',
            'cost_center_type_id.exists' => 'O tipo centro de custo não encontrado na base.',
            'code.required' => 'O código é obrigatório.',
            'code.string' => 'O código deve ser com números e pontos.',
            'code.min' => 'O código deve ter pelo menos 3 caracteres.',
            'code.max' => 'O código deve ter no máximo 12 caracteres.',
            'code.unique' => 'O código já está em uso.',
            'budget.required' => 'O orçamento é obrigatório.',
            'budget.numeric' => 'O orçamento deve ser um número válido.',
            'budget.min' => 'O orçamento deve ser no mínimo R$ 0,01.',
            'budget.max' => 'O orçamento não pode ultrapassar R$ 99.999,99.',
            'description.string' => 'A descrição deve ser um texto.',
        ];
    }
}
