<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType query()
 * @mixin \Eloquent
 */
class PaymentType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
