<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    protected $fillable = [
        'uuid',
        'reference',
        'amount',
        'payment_status_id',
        'payee_id',
        'cost_center_id',
        'expense_category_id',
        'due_at',
        'competence_date',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_at' => 'date',
        'competence_date' => 'date'
    ];

    public function payee(): BelongsTo
    {
        return $this->belongsTo(Payee::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
