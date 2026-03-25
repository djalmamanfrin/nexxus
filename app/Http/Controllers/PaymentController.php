<?php

namespace App\Http\Controllers;

use App\Actions\Attachment\AttachFileAction;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\PaymentStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
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
    public function store(Request $request, AttachFileAction $action): JsonResponse
    {
        $request->validate([
            'attachment' => ['required', 'file', 'image', 'max:5120'],
        ]);

        $attachment = null;
        $expense = DB::transaction(function () use ($request, $action, &$attachment) {
            $payment = Payment::create();
            $attachment = $action->execute(
                $payment,
                $request->file('attachment'),
                'attachments/payments');
        });

        return response()->json([
            'field' => 'expense_id',
            'value' => $expense->id,
            'label' => $attachment->original_name,
        ], Response::HTTP_CREATED);
    }

    /**
     * @throws Throwable
     */
    public function update(UpdatePaymentRequest $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validated();
        $payment->update($validated);

        return back()->with('success', 'Atualizado com sucesso');
    }

    public function uploadAttachment(Request $request, Payment $payment, AttachFileAction $attachFile)
    {
        $request->validate([
            'attachment' => ['required', 'file', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $payment->attachments()->delete();

        $attachFile->execute(
            $payment,
            $request->file('attachment'),
            'payments'
        );

        return back()->with('success', 'Arquivo atualizado');
    }
}
