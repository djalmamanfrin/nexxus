<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attachment extends Model
{
    protected $fillable = [
        'uuid',
        'disk',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'hash'
    ];

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(
            Payment::class,
            'payment_attachments'
        );
    }
}
