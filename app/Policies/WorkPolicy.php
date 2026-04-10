<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Work;

class WorkPolicy
{
    public function view(User $user, Work $work): bool
    {
        return $user->works()
            ->where('works.id', $work->id)
            ->exists();
    }
}
