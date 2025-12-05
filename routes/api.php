<?php

use App\Http\Controllers\PayLinkController;
use App\Http\Controllers\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/paylinks', [PayLinkController::class, 'create']);
Route::get('/paylinks/detail/{id}', [PayLinkController::class, 'show']);
Route::get('/paylinks/{user_id}', [PayLinkController::class, 'list_links']);

Route::post('/payments/webhook', [PaymentWebhookController::class, 'handle']);
