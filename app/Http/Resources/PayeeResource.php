<?php

namespace App\Http\Resources;

use App\Domain\ValidatorCastInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $payees = [
            'id' => $this->id,
            'name' => $this->name,
            'active' => [
                'label' => $this->is_pix_document ? 'Sim' : 'Não',
                'color' => $this->is_pix_document ? 'green' : 'yellow',
            ],
            'document_type' => $this->document_type,
            'pix_key_type' => $this->pix_key_type,
            'created_at' => $this->created_at?->toDateString(),
        ];

        if ($this->document instanceof ValidatorCastInterface) {
            $payees['document'] = [
                'value' => $this->document->value(),
                'formatted' => $this->document->formatted(),
                'mask' => $this->document->mask(),
            ];
        }

        if ($this->pix_key instanceof ValidatorCastInterface) {
            $payees['pix_key'] = [
                'value' => $this->pix_key->value(),
                'formatted' => $this->pix_key->formatted(),
                'mask' => $this->pix_key->mask(),
            ];
        }
        return $payees;
    }
}
