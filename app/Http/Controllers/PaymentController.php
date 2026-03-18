<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('payments/Index', [
            'payments' => $payments,
            'statuses' => PaymentStatus::select('id as value', 'name as label')->get(),
            'paymentTypes' => PaymentType::select('id as value', 'name as label')->get(),
            'search_by' => $request->search_by,
            'status' => $request->status,
            'paid_at' => $request->paid_at,
            'created_to' => $request->created_to,
        ]);
    }
}
