<?php

namespace App\Http\Controllers;

use App\Models\CostCenterType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CostCenterTypeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $paymentStatus = CostCenterType::query()
            ->select('id as value', 'name as label')
            ->get();

        return response()->json($paymentStatus);
    }
}
