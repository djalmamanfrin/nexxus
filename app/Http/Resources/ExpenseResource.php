<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'amount' => $this->amount,
            'description' => $this->description,
            'due_at' => $this->due_at?->toDateString(),
            'competence_date' => $this->competence_date?->toDateString(),
            'cost_center' => $this->whenLoaded('costCenter', function () {
                return [
                    'id' => $this->cost_center_id,
                    'code' => $this->costCenter?->code,
                ];
            }),
            'payee' => $this->whenLoaded('payee', function () {
                return [
                    'id' => $this->payee_id,
                    'name' => $this->payee?->name,
                ];
            }),
            'status' => $this->whenLoaded('status', function () {
                return [
                    'id' => $this->expense_status_id,
                    'name' => $this->status?->name,
                    'color' => $this->status?->color,
                ];
            }),
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->expense_category_id,
                    'name' => $this->category?->name,
                ];
            }),
            'attachments' => $this->whenLoaded('attachments', function () {
                return $this->attachments->map(fn ($file) => [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                ]);
            }),
        ];
    }
}
