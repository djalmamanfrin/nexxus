<?php

namespace App\Observers;

namespace App\Observers;

use App\Models\CostCenter;
use App\Models\CostCenterType;
use App\Models\Work;

class WorkObserver
{
    public function created(Work $work): void
    {
        $types = CostCenterType::where('is_active', true)
            ->select('id')
            ->get();

        foreach ($types as $type) {
            CostCenter::associateWorkAndType($work->id, $type->id);
        }
    }
}
