<?php

namespace App\Http\Controllers;

use App\Actions\Attachment\AttachFileAction;
use App\Models\Payment;
use App\Models\PaymentStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

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
     * @throws Throwable
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

    /**
     * @throws Throwable
     */
    public function update(Request $request, Payment $payment, AttachFileAction $attachFile): RedirectResponse
    {
        $validated = $request->validate([
            'expense_id' => ['nullable', 'integer'],
            'bank_account_id' => ['nullable', 'integer'],
            'payment_status_id' => ['nullable', 'integer'],
            'payment_type_id' => ['nullable', 'integer'],
            'amount' => ['nullable', 'numeric'],
            'paid_at' => ['nullable', 'date'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
        ]);

        DB::transaction(function () use ($validated, $request, $payment, $attachFile) {
            $payment->update($validated);
            if ($request->hasFile('attachment')) {
                $payment->attachments()->delete();
                $attachFile->execute(
                    $payment,
                    $request->file('attachment'),
                    'payments'
                );
            }
        });

        return back()->with('success', 'Atualizado com sucesso');
    }
}
