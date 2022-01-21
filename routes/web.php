<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\InvoiceController;
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
Route::post('/pay/{uid}', [CheckoutController::class, 'payDo'])->name('checkout.payDo');

Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
Route::get('/invoice/{uid}', [InvoiceController::class, 'view'])->name('invoice.view');
Route::get('/invoice/{uid}', [InvoiceController::class, 'check'])->name('invoice.check');
