<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Work extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function costCenters(): HasMany
    {
        return $this->hasMany(CostCenter::class);
    }
}
