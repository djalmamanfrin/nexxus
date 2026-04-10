<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class UserActiveWorkController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'work_id' => ['required', 'exists:works,id'],
        ]);

        $work = Work::findOrFail($data['work_id']);
        $request->user()->update([
            'active_work_id' => $work->id,
        ]);

        return back();
    }
}
