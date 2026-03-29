<?php

namespace App\Models;

use App\Models\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string|null $code
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CostCenter> $costCenters
 * @property-read int|null $cost_centers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereUuid($value)
 * @property string $ulid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereUlid($value)
 * @mixin \Eloquent
 */
class Work extends Model
{
    use HasUlid;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    public function costCenters(): HasMany
    {
        return $this->hasMany(CostCenter::class);
    }

    public function scopeFilter($query, $filters): void
    {
        $query->when($filters['search_by'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        });
    }
}
