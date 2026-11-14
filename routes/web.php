<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;

Route::get('/', function () {
    return redirect()->route('register');
});

Route::get('/pos', function () {
    $todaySales = \App\Models\Sale::whereDate('created_at', today())
        ->sum('total_price');

    $todayTransactions = \App\Models\Sale::whereDate('created_at', today())
        ->count();

    $totalProducts = \App\Models\Product::count();

    $lowStock = \App\Models\Product::where('stock_quantity', '<=', 5)
        ->count();

    $recentSales = \App\Models\Sale::with(['product', 'customer'])
        ->latest()
        ->take(5)
        ->get();

    return view('pos.dashboard', compact(
        'todaySales',
        'todayTransactions',
        'totalProducts',
        'lowStock',
        'recentSales'
    ));

})->middleware('auth')->name('pos.dashboard');


Route::view('/register', 'auth.register')->name('register');
Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

Route::post('/login', [LoginController::class, 'store'])
    ->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')->name('logout');

Route::view('/login', 'auth.login')->name('login');


// POS MODULE ROUTES
Route::middleware('auth')->group(function () {

    // Products
    Route::resource('products', ProductController::class);

    // Customers
    Route::resource('customers', CustomerController::class);

    // Sales
    Route::get('/sales/create', [SaleController::class, 'create'])
        ->name('sales.create');

    Route::post('/sales', [SaleController::class, 'store'])
        ->name('sales.store');

    Route::get('/sales', [SaleController::class, 'index'])
        ->name('sales.index');

});
