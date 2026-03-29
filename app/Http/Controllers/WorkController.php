<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        $works = Work::query()
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('works/Index', [
            'works' => $works,
            'search_by' => $request->search_by,
        ]);
    }

    public function options(): JsonResponse
    {
        $works = Work::query()
            ->select('id as value', 'name as label')
            ->get();

        return response()->json($works);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:60'],
        ]);

        $validated = $request->only('name');
        $work = Work::create($validated);

        return response()->json([
            'field' => 'work_id',
            'value' => $work->id,
            'label' => $work->name,
        ], Response::HTTP_CREATED);
    }

    public function update(Request  $request, Work $work)
    {

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'description' => 'nullable|string|min:3|max:255'
        ]);

        $validated = $request->only('name', 'is_active', 'description');
        $work->update($validated);

        return back()->with('success', 'Obra atualizada com sucesso');
    }

    public function destroy(Work $work)
    {
        $work->delete();
        return redirect()
            ->route('works.index')
            ->with('success', 'Obra apagada com sucesso!');
    }
}
