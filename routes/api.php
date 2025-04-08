<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomDomainController;

Route::get('/ping', function () {
    return response()->json([
        'pong' => true,
        'status' => 'Laravel API está respondendo com sucesso 🚀'
    ]);
});

Route::post('/custom-domains', [CustomDomainController::class, 'store']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
