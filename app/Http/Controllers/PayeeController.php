<?php

namespace App\Http\Controllers;

use App\Http\Resources\PayeeResource;
use App\Models\Payee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class PayeeController extends Controller
{
    public function index(Request $request)
    {
        $payees = Payee::query()
            ->with('attachments')
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('works/Index', [
            'works' => PayeeResource::collection($payees),
            'search_by' => $request->search_by,
        ]);
    }

    public function options(): JsonResponse
    {
        $payees = Payee::query()
            ->select('id as value', 'name as label')
            ->get();

        return response()->json($payees);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        $validated = $request->only('name');
        $payee = Payee::create($validated);

        return response()->json([
            'field' => 'payee_id',
            'value' => $payee->id,
            'label' => $payee->name,
        ], Response::HTTP_CREATED);
    }

    public function update(Request  $request, Payee $payee)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
        ]);
        $validated = $request->only('name');
        $payee->update($validated);

        return back()->with('success', 'Benefeciário atualizada com sucesso');
    }

    public function destroy(Payee $payee)
    {
        $payee->delete();
        return redirect()
            ->route('works.index')
            ->with('success', 'Benefeciário apagada com sucesso!');
    }
}
