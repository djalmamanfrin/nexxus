<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentStatusRequest;
use App\Models\PaymentStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    public function store(StorePaymentStatusRequest $request): RedirectResponse
    {
        PaymentStatus::create($request->validated());
        return back()->with('success', 'Status de pagamento criado!');
    }
}
