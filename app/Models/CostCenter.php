<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostCenter extends Model
{
    protected $fillable = [
        'work_id',
        'cost_center_type_id',
        'name',
        'description',
        'start_date',
        'expected_end_date',
        'budget'
    ];

    protected $casts = [
        'start_date' => 'date',
        'expected_end_date' => 'date',
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
}
