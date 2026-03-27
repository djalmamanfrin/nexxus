<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $color
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expense> $expenses
 * @property-read int|null $expenses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseStatus whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseStatus whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseStatus whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExpenseStatus extends Model
{
    const PENDING = 1;
    const DONE = 2;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'sort_order',
        'active'
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
