<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CostCenter> $costCenters
 * @property-read int|null $cost_centers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterType whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CostCenterType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'active'
    ];

    public function costCenters(): HasMany
    {
        return $this->hasMany(CostCenter::class);
    }
}
