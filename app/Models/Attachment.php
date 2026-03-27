<?php

namespace App\Models;

use App\Models\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Storage;

/**
 * @property int $id
 * @property string $uuid
 * @property string $hash
 * @property string $attachable_type
 * @property int $attachable_id
 * @property string $file_path
 * @property string|null $original_name
 * @property string|null $mime_type
 * @property int|null $size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereAttachableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereAttachableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereUuid($value)
 * @property string $ulid
 * @property-read string $url
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereUlid($value)
 * @mixin \Eloquent
 */
class Attachment extends Model
{
    use HasUlid;

    protected $fillable = [
        'disk',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'hash'
    ];

    protected $appends = ['url'];

    protected $hidden = ['file_path'];

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(
            Payment::class,
            'payment_attachments'
        );
    }

    public function getUrlAttribute(): string
    {
//        return Storage::disk('s3')->temporaryUrl(
//            $this->file_path,
//            now()->addMinutes(5) // expira em 5 minutos
//        );
        return Storage::url($this->file_path);
    }
}
