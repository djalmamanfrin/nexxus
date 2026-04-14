<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Casts\DateValueCast;
use App\Domain\VO\CurrencyValue;
use App\Models\Concerns\BelongsToActiveWork;
use App\Models\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $ulid
 * @property int $work_id
 * @property int $payment_status_id
 * @property int $payment_type_id
 * @property int|null $bank_account_id
 * @property CurrencyValue $amount
 * @property string|null $transaction_id
 * @property string|null $end_to_end_id
 * @property \App\Domain\VO\DateValue|null $paid_at
 * @property \App\Domain\VO\DateValue|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\BankAccount|null $bankAccount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expense> $expenses
 * @property-read int|null $expenses_count
 * @property-read \App\Models\PaymentStatus $status
 * @property-read \App\Models\PaymentType $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment filter($filters)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBankAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereEndToEndId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereWorkId($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use HasUlid, BelongsToActiveWork;

    protected $fillable = [
        'expense_id',
        'work_id',
        'bank_account_id',
        'payment_status_id',
        'amount',
        'payment_method',
        'transaction_id',
        'end_to_end_id',
        'paid_at'
    ];

    protected $casts = [
        'amount' => AmountCast::class,
        'paid_at' => DateValueCast::class,
        'created_at' => DateValueCast::class
    ];

    public function reconciliations(): HasMany
    {
        return $this->hasMany(Reconciliation::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function scopeFilter($query, $filters): void
    {
        $query->when($filters['search_by'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%$search%")
                    ->orWhere('end_to_end_id', 'like', "%$search%");
            })
                ->orWhereHas('attachments', function ($q) use ($search) {
                    $q->where('original_name', 'like', "%$search%");
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
