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
            'document' => $this->document,
            'document_type' => $this->document_type,
            'pix_key' => $this->pix_key,
            'pix_key_type' => $this->pix_key_type,
            'created_at' => $this->created_at?->toDateString(),
        ];
    }
}
