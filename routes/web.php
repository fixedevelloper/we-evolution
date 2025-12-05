<?php

use App\Http\Controllers\PayLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/pay/{reference}', [PayLinkController::class, 'viewPage']);
