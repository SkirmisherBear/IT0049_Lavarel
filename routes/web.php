<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MemberPortalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StaffController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::patch('/staff/{user}/role', [StaffController::class, 'updateRole'])->name('staff.role');
    Route::delete('/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');
});

// Smart Home Redirect: sends logged-in users to their own role dashboard
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin'  => redirect()->route('pos.dashboard'),
            'client' => redirect()->route('member.portal'),
            default  => redirect()->route('pos.dashboard'),
        };
    }

    return redirect()->route('login');
});

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// CLIENT / MEMBER PORTAL ROUTES (Clients Only)
Route::middleware(['auth', 'role:client'])->prefix('member')->name('member.')->group(function () {
    Route::get('/portal', [MemberPortalController::class, 'index'])->name('portal');
    Route::post('/bookings', [MemberPortalController::class, 'storeBooking'])->name('bookings.store');
    Route::patch('/bookings/{booking}/cancel', [MemberPortalController::class, 'cancelBooking'])->name('bookings.cancel');
});

// STAFF & BOSS POS MODULE ROUTES (Locked from Clients)
Route::middleware(['auth', 'role:admin,staff'])->group(function () {
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
    })->name('pos.dashboard');

    // Products
    Route::resource('products', ProductController::class);

    // Customers
    Route::resource('customers', CustomerController::class);

    // Sales
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
});