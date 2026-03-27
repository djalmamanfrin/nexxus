<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseStatus extends Model
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

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
