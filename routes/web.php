<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('register');
});
Route::get('/pos', function () {
    return view('pos.dashboard');
})->middleware('auth')->name('pos.dashboard');

Route::view('/register', 'auth.register')->name('register');
Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

Route::post('/login', [LoginController::class, 'store'])
    ->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')->name('logout');

Route::view('/login', 'auth.login')->name('login');
