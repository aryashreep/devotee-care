<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\UserController;

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('users.index');
    })->name('dashboard');

    Route::resource('users', UserController::class);
});
