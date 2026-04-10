<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereUpdatedAt($value)
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
