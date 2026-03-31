<?php

namespace App\Http\Requests\Payees;

use App\Domain\Documents\CPF;
use App\Domain\Documents\DocumentFactory;
use App\Rules\DocumentRule;
use Illuminate\Foundation\Http\FormRequest;

class PayeesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $document = $this->input('document');
        if (!empty($document)) {
            $document = $this->detectDocumentType($document);
        }

        $pixKey = $this->input('pix_key');
        if (!empty($pixKey)) {
            $pixKey = $this->detectPixKeyType($document);
        }

        $this->merge([
            'document_type' => $document,
            'pix_key_type' => $pixKey,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'document' => ['required', 'string', new DocumentRule()],
            'document_type' => ['required', 'string'],
            'is_pix_document' => ['required', 'boolean'],
            'pix_key' => ['nullable', 'string'],
            'pix_key_type' => ['nullable', 'string'],
        ];
    }

    private function detectDocumentType(string $document): string
    {
        $document = DocumentFactory::make($document);
        return $document->type()->value;
    }
}
