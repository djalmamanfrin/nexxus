<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\Payment;
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

        Payment::observe(PaymentObserver::class);
        Expense::observe(ExpenseObserver::class);
    }
}
