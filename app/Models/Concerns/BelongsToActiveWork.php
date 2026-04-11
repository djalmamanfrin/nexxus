<?php

namespace App\Models\Concerns;

use App\Models\Scopes\WorkScope;
use Illuminate\Database\Eloquent\Model;

trait BelongsToActiveWork
{
    protected static function bootBelongsToActiveWork(): void
    {
        static::creating(function (Model $model) {
            // se já foi setado manualmente, não sobrescreve
            if (!empty($model->work_id)) {
                return;
            }
            // evita erro em CLI / queue
            if (!auth()->check()) {
                return;
            }
            $model->work_id = auth()->user()->active_work_id;
        });

        static::addGlobalScope(new WorkScope());
    }
}
