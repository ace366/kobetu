<?php
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ログイン後のページ
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');
