<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentStatusRequest;
use App\Models\PaymentStatus;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PaymentStatusController extends Controller
{
    public function store(StorePaymentStatusRequest $request): JsonResponse
    {
        $paymentStatus = PaymentStatus::create($request->validated());
        return response()->json($paymentStatus->only('id', 'name', 'code'), Response::HTTP_CREATED);
    }
}
