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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CostCenter> $costCenters
 * @property-read int|null $cost_centers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterStatus whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterStatus whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterStatus whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostCenterStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CostCenterStatus extends Model
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

    public function costCenters(): HasMany
    {
        return $this->hasMany(CostCenter::class);
    }
}
