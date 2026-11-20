<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StoreController;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/dashboard', function () {
    return auth()->user()->role === 'client'
        ? redirect()->route('client.dashboard')
        : redirect()->route('pos.dashboard');
})->middleware('auth')->name('dashboard');

Route::prefix('client')
    ->name('client.')
    ->middleware(['auth', 'can:client'])
    ->group(function () {
        Route::get('/', fn () => redirect()->route('client.dashboard'));
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

        Route::get('/store', [StoreController::class, 'index'])->name('store');
        Route::post('/store/checkout', [StoreController::class, 'checkout'])->name('store.checkout');

        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

        Route::get('/progress', [ProgressController::class, 'index'])->name('progress');
        Route::post('/progress', [ProgressController::class, 'store'])->name('progress.store');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });

Route::middleware(['auth', 'can:staff'])
    ->group(function () {
        Route::get('/pos', function () {
            $todaySales = Sale::whereDate('created_at', today())->sum('total_price');
            $todayTransactions = Sale::whereDate('created_at', today())
                ->whereNotNull('transaction_code')
                ->distinct('transaction_code')
                ->count('transaction_code')
                + Sale::whereDate('created_at', today())
                    ->whereNull('transaction_code')
                    ->count();
            $totalProducts = Product::count();
            $lowStock = Product::where('stock_quantity', '<=', 5)->count();
            $recentSales = Sale::with(['product', 'customer'])->latest()->limit(5)->get();

            return view('pos.dashboard', compact(
                'todaySales',
                'todayTransactions',
                'totalProducts',
                'lowStock',
                'recentSales'
            ));
        })->name('pos.dashboard');

        Route::resource('products', ProductController::class);
        Route::resource('customers', CustomerController::class);
        Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    });
