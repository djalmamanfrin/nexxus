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
 * @property string $document
 * @property string|null $document_type
 * @property string|null $pix_key
 * @property string|null $pix_key_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expense> $expenses
 * @property-read int|null $expenses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee wherePixKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee wherePixKeyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee whereUuid($value)
 * @property string $ulid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payee whereUlid($value)
 * @mixin \Eloquent
 */
class Payee extends Model
{
    use HasUlid;

    protected $fillable = [
        'name',
        'document',
        'document_type',
        'pix_key',
        'pix_key_type'
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
