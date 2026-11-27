<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberPortalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
            'admin' => redirect()->route('pos.dashboard'),
            'client' => redirect()->route('member.portal'),
            default => redirect()->route('pos.dashboard'),
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

Route::view('/logout', 'auth.logout')
    ->middleware('auth')
    ->name('logout.confirm');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/dashboard', fn () => redirect('/'))->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
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

// CLIENT / MEMBER PORTAL ROUTES (Clients Only)
Route::middleware(['auth', 'role:client'])->prefix('member')->name('member.')->group(function () {
    Route::get('/portal', [MemberPortalController::class, 'index'])->name('portal');
    Route::post('/bookings', [MemberPortalController::class, 'storeBooking'])->name('bookings.store');
    Route::patch('/bookings/{booking}/cancel', [MemberPortalController::class, 'cancelBooking'])->name('bookings.cancel');
});

// STAFF & BOSS POS MODULE ROUTES (Locked from Clients)
Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/pos', [DashboardController::class, 'index'])->name('pos.dashboard');

    // Products
    Route::resource('products', ProductController::class)->except(['show']);

    // Customers
    Route::resource('customers', CustomerController::class)->except(['show']);

    // Sales
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
});
