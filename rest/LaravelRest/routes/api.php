<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('registerClient',[ClientController::class,'RegisterClient']);
Route::post('rechargeAccount',[ClientController::class,'RechargeAccount']);
Route::post('payment',[PaymentController::class,'NewPayment']);
Route::get('confirmPayment',[PaymentController::class,'ConfirmPayment']);
Route::post('getBalance',[ClientController::class,'GetBalance']);