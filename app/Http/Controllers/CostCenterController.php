<?php

namespace App\Http\Controllers;

use App\Http\Requests\CostCenters\StoreCostCenterRequest;
use App\Http\Requests\UpdateCostCenterRequest;
use App\Http\Resources\CostCenterResource;
use App\Http\Resources\CostCenterTypeResource;
use App\Models\CostCenter;
use App\Models\CostCenterStatus;
use App\Models\CostCenterType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CostCenterController extends Controller
{
    public function index(Request $request)
    {
        $costCenters = CostCenter::query()
            ->with(['work', 'status', 'type'])
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('cost_centers/Index', [
            'cost_centers' => CostCenterResource::collection($costCenters),
            'types' => CostCenterTypeResource::collection(CostCenterType::paginate()),
            'statuses' => CostCenterStatus::select('id as value', 'name as label', 'color')->get(),
            'search_by' => $request->search_by,
            'status' => $request->status,
        ]);
    }

    public function options(): JsonResponse
    {
        $costCenters = CostCenter::with('work')
            ->get()
            ->map(fn ($cc) => [
                'value' => $cc->id,
                'label' => "{$cc->work->name} - {$cc->code}",
            ]);

        return response()->json($costCenters);
    }

    public function store(StoreCostCenterRequest $request): RedirectResponse
    {
        CostCenter::create($request->validated());
        return back()->with(['success' => 'Centro de custo criada com sucesso']);
    }

    public function update(UpdateCostCenterRequest  $request, CostCenter $costCenter)
    {
        $validated = $request->validated();
        $costCenter->update($validated);

        return back()->with('success', 'Centro de custo atualizado com sucesso');
    }

    public function destroy(CostCenter $costCenter): RedirectResponse
    {
        $costCenter->delete();
        return back()->with('success', 'Centro de custo apagado com sucesso!');
    }
}
