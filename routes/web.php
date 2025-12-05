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
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ProfessionController;
use App\Http\Controllers\SevaController;
use App\Http\Controllers\ShikshaLevelController;
use App\Http\Controllers\BhaktiSadanController;

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('users.index');
    })->name('dashboard');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile.show');
    Route::post('/users/{user}/toggle-enabled', [UserController::class, 'toggleEnabled'])->name('users.toggle-enabled');
    Route::resource('users', UserController::class);
    Route::resource('educations', EducationController::class);
    Route::resource('professions', ProfessionController::class);
    Route::resource('sevas', SevaController::class);
    Route::resource('shiksha-levels', ShikshaLevelController::class);
    Route::resource('bhakti-sadans', BhaktiSadanController::class);
});
