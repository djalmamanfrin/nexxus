<?php

namespace App\Http\Resources;

use App\Domain\VO\CurrencyValue;
use App\Support\Format;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CostCenterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'expected_end_date' => $this->expected_end_date,
            'created_at' => $this->created_at,
            'status' => $this->whenLoaded('status', function () {
                return [
                    'id' => $this->status?->id,
                    'name' => $this->status?->name,
                    'color' => $this->status?->color,
                ];
            }),
            'budget' => new CurrencyValue($this->budget),
            'work' => $this->whenLoaded('work', function () {
                return [
                    'id' => $this->work_id,
                    'name' => $this->work?->name,
                ];
            }),
            'type' => $this->whenLoaded('type', function () {
                return [
                    'id' => $this->type->id,
                    'name' => $this->type?->name,
                    'code' => $this->type?->code,
                ];
            }),
        ];
    }
}
