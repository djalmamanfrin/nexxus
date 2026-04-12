<?php

namespace App\Http\Resources;

use App\Support\Format;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'amount' => [
                'value' => $this->amount,
                'formatted' => Format::money($this->amount),
            ],
            'description' => $this->description,
            'due_at' => $this->due_at,
            'competence_date' => [
                'value' => $this->competence_date?->value('Y-m'),
                'formatted' => $this->competence_date?->formatted('m/Y'),
            ],
            'created_at' => $this->created_at,
            'cost_center' => $this->whenLoaded('costCenter', function () {
                return [
                    'id' => $this->cost_center_id,
                    'code' => $this->costCenter?->code,
                    'label' => "{$this->costCenter?->work?->code} - {$this->costCenter?->code}",
                ];
            }),
            'payee' => $this->whenLoaded('payee', function () {
                return [
                    'id' => $this->payee_id,
                    'name' => $this->payee?->name,
                    'document' => $this->payee?->document?->formatted(),
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
            'attachments' => $this->whenLoaded(
                'attachments',
                fn () => $this->attachments->map(fn ($file) => [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'url' => $file->url,
                ]),
                []
            ),
        ];
    }
}
