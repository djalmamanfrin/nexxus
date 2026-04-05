<?php

namespace App\Observers;

namespace App\Observers;

use App\Models\CostCenter;
use App\Models\CostCenterType;
use App\Models\Work;

class CostCenterTypeObserver
{
    public function created(CostCenterType $type): void
    {
        $works = Work::select('id')->get();
        foreach ($works as $work) {
            CostCenter::associateWorkAndType($work->id, $type->id);
        }
    }
}
