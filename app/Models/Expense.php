<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Casts\DateValueCast;
use App\Domain\VO\CurrencyValue;
use App\Models\Concerns\BelongsToActiveWork;
use App\Models\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $ulid
 * @property int $work_id
 * @property int $expense_status_id
 * @property int|null $payee_id
 * @property int|null $cost_center_id
 * @property int $expense_category_id
 * @property string|null $reference
 * @property CurrencyValue $amount
 * @property \App\Domain\VO\DateValue|null $due_at
 * @property \App\Domain\VO\DateValue|null $competence_date
 * @property string|null $description
 * @property \App\Domain\VO\DateValue|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\ExpenseCategory $category
 * @property-read \App\Models\CostCenter|null $costCenter
 * @property-read \App\Models\Payee|null $payee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\ExpenseStatus $status
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereExpenseStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense wherePayeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereWorkId($value)
 * @mixin \Eloquent
 */
class Expense extends Model
{
    use HasUlid, BelongsToActiveWork;

    protected $fillable = [
        'reference',
        'amount',
        'work_id',
        'payee_id',
        'cost_center_id',
        'expense_status_id',
        'expense_category_id',
        'due_at',
        'competence_date',
        'description'
    ];

    protected $casts = [
        'amount' => AmountCast::class,
        'due_at' => DateValueCast::class,
        'competence_date' => DateValueCast::class,
        'created_at' => DateValueCast::class,
    ];

    public function payee(): BelongsTo
    {
        return $this->belongsTo(Payee::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ExpenseStatus::class, 'expense_status_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class)
            ->withPivot(['amount', 'linked_at'])
            ->whereColumn('payments.work_id', 'expenses.work_id')
            ->withTimestamps();
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function scopeFilter($query, $filters): void
    {
        $query->when($filters['search_by'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                    ->orWhere('reference', 'like', "%$search%");
            })
                ->orWhereHas('attachments', function ($q) use ($search) {
                    $q->where('original_name', 'like', "%$search%");
                })

            // campos do payee
            ->orWhereHas('payee', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('document', 'like', "%$search%")
                    ->orWhere('pix_key', 'like', "%$search%");
            });
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('expense_status_id', $status);
        });

        $query->when($filters['due_from'] ?? null, function ($query, $date) {
            $query->whereDate('due_at', '>=', $date);
        });

        $query->when($filters['due_to'] ?? null, function ($query, $date) {
            $query->whereDate('due_at', '<=', $date);
        });
    }
}
