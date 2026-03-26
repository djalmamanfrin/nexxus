<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $paymentStatus = PaymentType::query()
            ->select('id as value', 'name as label')
            ->get();

        return response()->json($paymentStatus);
    }
}
