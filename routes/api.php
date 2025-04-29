<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XenditCallbackController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Xendit webhook endpoint - without middleware web
Route::post('/webhooks/xendit', [XenditCallbackController::class, 'handleWebhook']);
