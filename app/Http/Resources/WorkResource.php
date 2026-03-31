<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'active' => [
                'label' => $this->is_active ? 'Ativo' : 'Inativo',
                'color' => $this->is_active ? 'green' : 'red',
            ],
            'created_at' => $this->created_at?->toDateString(),
        ];
    }
}
