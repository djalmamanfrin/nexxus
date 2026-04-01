<?php

namespace App\Http\Requests\Payees;

use App\Domain\Documents\DocumentFactory;
use App\Domain\PixKey\PixKeyFactory;
use App\Rules\DocumentRule;
use App\Rules\PixKeyRule;
use Illuminate\Foundation\Http\FormRequest;

class PayeesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $documentType = null;
        $document = $this->input('document');
        if (!empty($document)) {
            $documentType = $this->detectDocumentType($document);
        }


        $isPixDocument = $this->boolean('is_pix_document');
        $pixType = null;
        $pixKey = $isPixDocument ? $document : $this->input('pix_key');
        if (!empty($pixKey)) {
            $pixType = $this->detectPixKeyType($pixKey);
        }

        $this->merge([
            'document_type' => $documentType,
            'pix_key_type' => $pixType,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'document' => ['required', 'string', new DocumentRule()],
            'document_type' => ['required', 'string'],
            'is_pix_document' => ['required', 'boolean'],
            'pix_key' => ['nullable', 'string', new PixKeyRule()],
            'pix_key_type' => ['nullable', 'string'],
        ];
    }

    private function detectDocumentType(string $document): string
    {
        $document = DocumentFactory::make($document);
        return $document->type()->value;
    }

    private function detectPixKeyType(string $pixKey): string
    {
        $pixKey = PixKeyFactory::make($pixKey);
        return $pixKey->type()->value;
    }
}
