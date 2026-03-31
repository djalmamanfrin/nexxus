<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'document' => [
                'value' => $this->document->value(),
                'formatted' => $this->document->formatted(),
                'mask' => $this->document->mask(),
            ],
            'active' => [
                'label' => $this->is_pix_document ? 'Sim' : 'Não',
                'color' => $this->is_pix_document ? 'green' : 'yellow',
            ],
            'document_type' => $this->document_type,
            'pix_key' => $this->pix_key,
            'pix_key_type' => $this->pix_key_type,
            'created_at' => $this->created_at?->toDateString(),
        ];
    }
}
