<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::resource('invoices', InvoiceController::class);
//public function downloadInvoiceXml($invoiceId)
Route::get('invoices/{invoice}/download-xml', [InvoiceController::class, 'downloadInvoiceXml'])->name('invoices.download-xml');
Route::get('invoices/vat-rates/{country}', [InvoiceController::class, 'getVatRates'])->name('invoices.vat-rates');
Route::resource('customers', CustomerController::class);

