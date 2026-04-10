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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentStatus whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentStatus whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentStatus whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentStatus extends Model
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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
