<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    CompanyController,
    CustomerController,
    ProductController,
    OrderController,
    InvoiceController,
    PageController
};

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('order', [PageController::class, 'order_form'])->name('order.form');
Route::post('order', [PageController::class, 'place_order'])->name('order.create');

// Company Management Routes
Route::apiResource('companies', CompanyController::class);

// Customer Management Routes
Route::apiResource('customers', CustomerController::class);

// Product Catalog Routes
Route::apiResource('products', ProductController::class);

// Order & Delivery Routes
Route::apiResource('orders', OrderController::class);

// Invoice Management Routes
Route::apiResource('invoices', InvoiceController::class);

// Additional Invoice-specific Routes
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generatePDF'])->name('invoices.pdf');
Route::get('invoices/{invoice}/preview', [InvoiceController::class, 'preview'])->name('invoice.preview');
Route::post('/invoices/{invoice}/issue', [InvoiceController::class, 'issue'])->name('invoice.issue');

Route::get('config/tax-rate', function () {
    return response()->json(['gst_rate' => 10]);
});
