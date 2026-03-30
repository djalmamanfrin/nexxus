<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payees\StorePayeesRequest;
use App\Http\Resources\PayeeResource;
use App\Models\Payee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PayeeController extends Controller
{
    public function index(Request $request)
    {
        $payees = Payee::query()
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('payees/Index', [
            'payees' => PayeeResource::collection($payees),
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

    public function store(StorePayeesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->boolean('is_pix_document')) {
            $data['pix_key'] = $data['document'];
            $data['pix_key_type'] = $data['document_type'];
        }

        $payee = Payee::create($data);

        return redirect()
            ->route('payees.index')
            ->with([
            'success' => 'Beneficiário criado com sucesso',
            'created' => [
                'field' => 'payee_id',
                'value' => $payee->id,
                'label' => $payee->name,
            ],
        ]);
    }

//    public function store(Request $request): JsonResponse
//    {
//        $request->validate([
//            'name' => ['required', 'string', 'min:3', 'max:255'],
//        ]);
//
//        $validated = $request->only('name');
//        $payee = Payee::create($validated);
//
//        return response()->json([
//            'field' => 'payee_id',
//            'value' => $payee->id,
//            'label' => $payee->name,
//        ], Response::HTTP_CREATED);
//    }

    public function update(Request  $request, Payee $payee)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'document' => ['required', 'string'],
            'document_type' => ['required', 'string'],
            'is_pix_document' => ['required', 'boolean'],
            'pix_key' => ['nullable', 'string'],
            'pix_key_type' => ['nullable', 'string'],
        ]);

        $data = $request->all();
        if ($request->boolean('is_pix_document')) {
            $data['pix_key'] = $data['document'];
            $data['pix_key_type'] = $data['document_type'];
        }
        $payee->update($data);

        return back()
            ->with('success', 'Benefeciário atualizada com sucesso');
    }

    public function destroy(Payee $payee)
    {
        $payee->delete();
        return redirect()
            ->route('payees.index')
            ->with('success', 'Benefeciário apagado com sucesso!');
    }
}
