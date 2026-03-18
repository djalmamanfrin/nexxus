<?php

namespace App\Http\Controllers;

use App\Actions\Attachment\StorePaymentAction;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use Illuminate\Http\RedirectResponse;
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

    /**
     * @throws \Throwable
     */
    public function store(Request $request, StorePaymentAction $action): RedirectResponse
    {
        $request->validate([
            'attachment' => ['required', 'file', 'image', 'max:5120'],
        ]);

        $action->execute([
            'attachment' => $request->file('attachment'),
        ]);

        return back()->with('success', 'Pagamento criado!');
    }
}
