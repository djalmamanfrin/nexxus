<?php

namespace App\Observers;

use App\Models\CostCenter;
use App\Models\CostCenterStatus;

class CostCenterObserver
{
    public function saving(CostCenter $costCenter): void
    {
        if ($this->shouldMarkAsConcluded($costCenter)) {
            $costCenter->cost_center_status_id = CostCenterStatus::DONE;
        }
    }
    private function shouldMarkAsConcluded(CostCenter $costCenter): bool
    {
        return
            $costCenter->cost_center_status_id !== CostCenterStatus::DONE
            && filled($costCenter->code)
            && $costCenter->budget > 0
            && $costCenter->start_date?->value() !== null
            && $costCenter->expected_end_date?->value() !== null;
    }
}
