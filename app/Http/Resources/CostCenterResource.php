<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CostCenterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'description' => $this->description,
            'budget' => (float) $this->budget,
            'start_date' => $this->start_date?->toDateString(),
            'expected_end_date' => $this->expected_end_date?->toDateString(),
            'created_at' => $this->created_at?->toDateString(),
            'work' => $this->whenLoaded('work', function () {
                return [
                    'id' => $this->work_id,
                    'name' => $this->work?->name,
                ];
            }),
            'type' => $this->whenLoaded('type', function () {
                return [
                    'id' => $this->type_id,
                    'name' => $this->type?->name,
                ];
            }),
        ];
    }
}
