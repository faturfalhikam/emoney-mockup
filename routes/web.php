<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\InvoiceController;

use App\Http\Controllers\Dana\CheckoutController as DanaCheckoutController;
use App\Http\Controllers\Dana\Controller as DanaController;
use App\Http\Controllers\Dana\InvoiceController as DanaInvoiceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Controller::class, 'index'])->name('app.index');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

Route::get('/checkout/{uid}', [CheckoutController::class, 'check'])->name('checkout.check');
Route::get('/pay/{uid}', [CheckoutController::class, 'pay'])->name('checkout.pay');
Route::post('/pay/{uid}/ovo', [CheckoutController::class, 'payOvo'])->name('checkout.pay.ovo');
Route::post('/pay/{uid}/shopeepay', [CheckoutController::class, 'payShopee'])->name('checkout.pay.shopeepay');
Route::post('/pay/{uid}/speedcash', [CheckoutController::class, 'paySpeedcash'])->name('checkout.pay.speedcash');
Route::post('/pay/{uid}/dana', [CheckoutController::class, 'payDana'])->name('checkout.pay.dana');
Route::post('/pay/{uid}', [CheckoutController::class, 'payDo'])->name('checkout.payDo');

Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
Route::get('/invoice/{uid}', [InvoiceController::class, 'check'])->name('invoice.check');
Route::delete('/invoice/{uid}', [InvoiceController::class, 'delete'])->name('invoice.delete');

Route::post('/shopeepay/callback', [InvoiceController::class, 'shopeepayCallback'])->name('shopeepay.callback');
Route::get('/shopeepay/status/{uid}', [InvoiceController::class, 'shopeepayStatus'])->name('shopeepay.status');
Route::get('/shopeepay/{uid}', [InvoiceController::class, 'shopeepayRedirect'])->name('shopeepay.redirect');

Route::get('/status-dana/{uid}', [InvoiceController::class, 'danaRedirect'])->name('dana.redirect');
