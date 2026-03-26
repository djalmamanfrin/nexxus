<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $paymentStatus = BankAccount::query()
            ->select('id as value', 'name as label')
            ->get();

        return response()->json($paymentStatus);
    }
}
