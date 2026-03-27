<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebugController;

Route::post('/debug/user/{userId}/enable', [DebugController::class, 'enable']);
Route::post('/debug/user/{userId}/disable', [DebugController::class, 'disable']);
