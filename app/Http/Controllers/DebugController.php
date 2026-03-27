<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DebugController extends Controller
{
    public function enableDebug(Request $request, int $userId): JsonResponse
    {
        $exists = User::where('id', $userId)->exists();
        if (!$exists) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        Cache::put("debug:user:{$userId}", true, now()->addMinutes(10));

        return response()->json([
            'message' => "Debug ativado para user {$userId}"
        ]);
    }

    public function disableDebug(Request $request, int $userId): JsonResponse
    {
        $token = $request->header('X-Debug-Secret');
        if ($token !== config('app.debug_secret')) {
            abort(403, 'Unauthorized');
        }

        $exists = User::where('id', $userId)->exists();
        if (!$exists) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        Cache::forget("debug:user:{$userId}");

        return response()->json([
            'message' => "Debug desativado para user {$userId}"
        ]);
    }
}
