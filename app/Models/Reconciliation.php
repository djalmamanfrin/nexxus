<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Casts\DateValueCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reconciliation extends Model
{
    protected $table = 'reconciliation';

    protected $fillable = [
        'expense_id',
        'payment_id',
        'amount',
        'linked_at',
    ];

    protected $casts = [
        'amount' => AmountCast::class,
        'linked_at' => DateValueCast::class,
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
