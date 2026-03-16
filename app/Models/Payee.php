<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payee extends Model
{
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
