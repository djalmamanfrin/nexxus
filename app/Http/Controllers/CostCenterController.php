<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCostCenterRequest;
use App\Http\Requests\StoreExpenseStatusRequest;
use App\Models\CostCenter;
use App\Models\ExpenseStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CostCenterController extends Controller
{
    public function index(): JsonResponse
    {
        $paymentStatus = CostCenter::query()
            ->select('id as value', 'code as label')
            ->get();

        return response()->json($paymentStatus);
    }
    public function store(StoreCostCenterRequest $request): JsonResponse
    {
        $paymentStatus = CostCenter::create($request->validated());
        return response()->json($paymentStatus->only('id', 'code'), Response::HTTP_CREATED);
    }
}
