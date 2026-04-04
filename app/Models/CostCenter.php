<?php

namespace App\Models;

use App\Casts\DateValueCast;
use App\Domain\VO\DateValue;
use App\Support\Logger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $work_id
 * @property int $cost_center_type_id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $expected_end_date
 * @property numeric|null $budget
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expense> $expenses
 * @property-read int|null $expenses_count
 * @property-read \App\Models\CostCenterType $type
 * @property-read \App\Models\Work $work
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter whereCostCenterTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter whereExpectedEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter whereWorkId($value)
 * @property string $code
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter filter($filters)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenter whereCode($value)
 * @mixin \Eloquent
 */
class CostCenter extends Model
{
    protected $fillable = [
        'work_id',
        'cost_center_type_id',
        'code',
        'description',
        'start_date',
        'expected_end_date',
        'budget'
    ];

    protected $casts = [
        'start_date' => DateValueCast::class,
        'expected_end_date' => DateValueCast::class,
        'created_at' => DateValueCast::class,
        'budget' => 'decimal:2'
    ];

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CostCenterType::class, 'cost_center_type_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function scopeFilter($query, $filters): void
    {
        $query->when($filters['search_by'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                    ->orWhere('budget', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            })
                ->orWhereHas('work', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                });
        });
    }

    public function getIsConcludedAttribute(): bool
    {
        return $this->isConcluded();
    }

    private function isConcluded(): bool
    {
        return filled($this->code)
            && $this->budget > 0
            && $this->start_date?->value() !== null
            && $this->expected_end_date?->value() !== null;
    }
}
