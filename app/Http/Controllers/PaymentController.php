<?php

namespace App\Http\Controllers;

use App\Actions\Attachment\AttachFileAction;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->with('attachments')
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('payments/Index', [
            'payments' => $payments,
            'statuses' => PaymentStatus::select('id as value', 'name as label')->get(),
            'search_by' => $request->search_by,
            'status' => $request->status,
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request, AttachFileAction $action): RedirectResponse
    {
        $request->validate([
            'attachment' => ['required', 'file', 'image', 'max:5120'],
        ]);

        DB::transaction(function () use ($request, $action) {
            $payment = Payment::create();
            $action->execute($payment, $request->file('attachment'), 'attachments/payments');
        });

        return back()->with('success', 'Pagamento criado!');
    }

    public function edit(Payment $payment): Response
    {
        return Inertia::render('payments/Edit', [
            'payment' => $payment->with('attachments')->first(),
            'statuses' => PaymentStatus::select('id as value', 'name as label')->get(),
        ]);
    }
}
