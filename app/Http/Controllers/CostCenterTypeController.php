<?php

namespace App\Http\Controllers;

use App\Models\CostCenterType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:60'],
            'code' => ['nullable', 'string', 'size:5'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        CostCenterType::create($validated);

        return back()->with(['success' => 'Tipo de centro de custo criado com sucesso']);
    }
}
