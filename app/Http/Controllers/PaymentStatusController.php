<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentStatusRequest;
use App\Models\PaymentStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentStatusController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $paymentStatus = PaymentStatus::query()
            ->select('id as value', 'name as label')
            ->get();

        return response()->json($paymentStatus);
    }
    public function store(StorePaymentStatusRequest $request): JsonResponse
    {
        $paymentStatus = PaymentStatus::create($request->validated());
        return response()->json($paymentStatus->only('id', 'name', 'slug'), Response::HTTP_CREATED);
    }
}
