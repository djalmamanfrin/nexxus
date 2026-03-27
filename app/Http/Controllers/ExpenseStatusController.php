<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseStatusRequest;
use App\Models\ExpenseStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpenseStatusController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $paymentStatus = ExpenseStatus::query()
            ->select('id as value', 'name as label', 'color')
            ->get();

        return response()->json($paymentStatus);
    }
    public function store(StoreExpenseStatusRequest $request): JsonResponse
    {
        $paymentStatus = ExpenseStatus::create($request->validated());
        return response()->json($paymentStatus->only('id', 'name', 'slug'), Response::HTTP_CREATED);
    }
}
