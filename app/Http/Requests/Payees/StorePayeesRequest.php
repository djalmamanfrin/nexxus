<?php

namespace App\Http\Requests\Payees;

use Illuminate\Foundation\Http\FormRequest;

class StorePayeesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'document' => ['required', 'string'],
            'document_type' => ['required', 'string'],
            'is_pix_document' => ['required', 'boolean'],
            'pix_key' => ['nullable', 'string'],
            'pix_key_type' => ['nullable', 'string'],
        ];
    }
}
