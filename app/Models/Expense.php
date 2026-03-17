<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $uuid
 * @property int $payment_status_id
 * @property int|null $payee_id
 * @property int|null $cost_center_id
 * @property int|null $expense_category_id
 * @property string|null $reference
 * @property numeric $amount
 * @property \Illuminate\Support\Carbon|null $due_at
 * @property \Illuminate\Support\Carbon|null $competence_date
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ExpenseCategory|null $category
 * @property-read \App\Models\CostCenter|null $costCenter
 * @property-read \App\Models\Payee|null $payee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense filter($filters)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCompetenceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCostCenterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDueAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereExpenseCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense wherePayeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense wherePaymentStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUuid($value)
 * @mixin \Eloquent
 */
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

    public function scopeFilter($query, $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                    ->orWhere('reference', 'like', "%$search%");
            })

            // campos do payee
            ->orWhereHas('payee', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('document', 'like', "%$search%")
                    ->orWhere('pix_key', 'like', "%$search%");
            });
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('payment_status_id', $status);
        });

        $query->when($filters['due_from'] ?? null, function ($query, $date) {
            $query->whereDate('due_at', '>=', $date);
        });

        $query->when($filters['due_to'] ?? null, function ($query, $date) {
            $query->whereDate('due_at', '<=', $date);
        });
    }
}
