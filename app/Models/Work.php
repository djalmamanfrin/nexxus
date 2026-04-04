<?php

namespace App\Models;

use App\Casts\DateValueCast;
use App\Models\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
 * @property int $is_active
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work filter($filters)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereIsActive($value)
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

    protected $casts = [
        'created_at' => DateValueCast::class,
    ];

    public function costCenters(): HasMany
    {
        return $this->hasMany(CostCenter::class);
    }

    public function setCodeAttribute(?string $value): void
    {
        $this->attributes['code'] = $value ? strtoupper(trim($value)) : null;
    }

    public function scopeFilter($query, $filters): void
    {
        $query->when($filters['search_by'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        });

        $query->when(!is_null($filters['is_active'] ?? null),
            fn ($q) => $q->where('is_active', $filters['is_active'])
        );
    }

    public static function abbreviate(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));

        $ignore = ['de', 'da', 'do', 'dos', 'das', 'e', '&'];
        $words = array_filter($words, fn ($w) => !in_array(Str::lower($w), $ignore));

        $abbr = collect($words)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');

        $abbr = Str::upper($abbr);

        if (strlen($abbr) < 5) {
            $fallback = Str::upper(preg_replace('/\s+/', '', $name));
            $abbr = Str::substr($fallback, 0, 5);
        }

        return Str::substr($abbr, 0, 5);
    }
}
