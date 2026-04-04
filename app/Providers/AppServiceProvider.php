<?php

namespace App\Providers;

use App\Models\CostCenter;
use App\Models\Expense;
use App\Models\Payment;
use App\Observers\CostCenterObserver;
use App\Observers\ExpenseObserver;
use App\Observers\PaymentObserver;
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
        Expense::observe(ExpenseObserver::class);
        Payment::observe(PaymentObserver::class);
    }
}
