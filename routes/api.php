<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\PayLinkController;
use App\Http\Controllers\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/paylinks', [PayLinkController::class, 'create']);
Route::get('/paylinks/detail/{id}', [PayLinkController::class, 'show']);
Route::get('/paylinks/{user_id}', [PayLinkController::class, 'list_links']);

Route::post('/payments/webhook', [PaymentWebhookController::class, 'handle']);

Route::prefix('bills')->group(function () {
    Route::get('/', [BillController::class, 'index']);             // Liste factures
    Route::get('/{id}', [BillController::class, 'show']);         // Détail facture
    Route::post('/', [BillController::class, 'store']);           // Créer facture
    Route::post('/{id}/validate', [BillController::class, 'validateBill']); // Valider
    Route::post('/{id}/pay', [BillController::class, 'pay']);     // Payer
});
