<?php

namespace App\Providers;

use App\Models\CostCenter;
use App\Models\CostCenterType;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Reconciliation;
use App\Models\Work;
use App\Observers\CostCenterObserver;
use App\Observers\CostCenterTypeObserver;
use App\Observers\ExpenseObserver;
use App\Observers\PaymentObserver;
use App\Observers\ReconciliationObserver;
use App\Observers\WorkObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        CostCenter::observe(CostCenterObserver::class);
        CostCenterType::observe(CostCenterTypeObserver::class);
        Expense::observe(ExpenseObserver::class);
        Payment::observe(PaymentObserver::class);
        Reconciliation::observe(ReconciliationObserver::class);
        Work::observe(WorkObserver::class);
    }
}
