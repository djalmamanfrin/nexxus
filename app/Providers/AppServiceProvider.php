<?php

namespace App\Providers;

use App\Models\CostCenter;
use App\Models\CostCenterType;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Work;
use App\Observers\CostCenterObserver;
use App\Observers\CostCenterTypeObserver;
use App\Observers\ExpenseObserver;
use App\Observers\PaymentObserver;
use App\Observers\WorkObserver;
use App\Policies\WorkPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Work::class, WorkPolicy::class);

        CostCenter::observe(CostCenterObserver::class);
        CostCenterType::observe(CostCenterTypeObserver::class);
        Expense::observe(ExpenseObserver::class);
        Payment::observe(PaymentObserver::class);
        Work::observe(WorkObserver::class);
    }
}
