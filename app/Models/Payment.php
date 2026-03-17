<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $uuid
 * @property int $payment_status_id
 * @property int $expense_id
 * @property int|null $bank_account_id
 * @property numeric $amount
 * @property string $payment_method
 * @property string|null $transaction_id
 * @property string|null $end_to_end_id
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\BankAccount|null $bankAccount
 * @property-read \App\Models\Expense $expense
 * @property-read \App\Models\PaymentStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBankAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereEndToEndId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereExpenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment filter($filters)
 * @mixin \Eloquent
 */
class Payment extends Model
{
    protected $fillable = [
        'expense_id',
        'bank_account_id',
        'payment_status_id',
        'amount',
        'payment_method',
        'transaction_id',
        'end_to_end_id',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function attachments(): BelongsToMany
    {
        return $this->belongsToMany(
            Attachment::class,
            'payment_attachments'
        );
    }

    public function scopeFilter($query, $filters): void
    {
        $query->when($filters['search_by'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%$search%")
                    ->orWhere('end_to_end_id', 'like', "%$search%");
            })

                // campos do bank_account
                ->orWhereHas('bankAccount', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('account_number', 'like', "%$search%")
                        ->orWhere('document', 'like', "%$search%");
                });
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('payment_status_id', $status);
        });

        $query->when($filters['paid_at'] ?? null, function ($query, $date) {
            $query->whereDate('paid_at', '>=', $date);
        });

        $query->when($filters['created_at'] ?? null, function ($query, $date) {
            $query->whereDate('created_at', '<=', $date);
        });
    }
}
