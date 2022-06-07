<?php

use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\ConnectionRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| userConnection Routes
|--------------------------------------------------------------------------
*/


Route::post('connection_requests/get_sent_request', [ConnectionRequestController::class, 'get_sent_request']);
Route::post('connection_requests/get_received_request', [ConnectionRequestController::class, 'get_received_request']);
Route::post('connections/get_common_connection', [ConnectionController::class, 'get_common_connection']);
Route::get('connections/get_suggestions', [ConnectionController::class, 'get_suggestions']);
Route::resource('connection_requests', ConnectionRequestController::class);
Route::resource('connections', ConnectionController::class);
