<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCostCenterRequest;
use App\Http\Resources\CostCenterResource;
use App\Models\CostCenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CostCenterController extends Controller
{
    public function index(Request $request)
    {
        $costCenters = CostCenter::query()
            ->with(['work', 'type'])
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('cost_centers/Index', [
            'cost_centers' => CostCenterResource::collection($costCenters),
            'search_by' => $request->search_by,
        ]);
    }

    public function options(): JsonResponse
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
