<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::prefix('register')->name('register.')->group(function () {
    Route::get('step-1', [RegisterController::class, 'showStep1'])->name('step1.show');
    Route::post('step-1', [RegisterController::class, 'storeStep1'])->name('step1.store');
    Route::get('step-2', [RegisterController::class, 'showStep2'])->name('step2.show');
    Route::post('step-2', [RegisterController::class, 'storeStep2'])->name('step2.store');
    Route::get('step-3', [RegisterController::class, 'showStep3'])->name('step3.show');
    Route::post('step-3', [RegisterController::class, 'storeStep3'])->name('step3.store');
    Route::get('step-4', [RegisterController::class, 'showStep4'])->name('step4.show');
    Route::post('step-4', [RegisterController::class, 'storeStep4'])->name('step4.store');
    Route::get('step-5', [RegisterController::class, 'showStep5'])->name('step5.show');
    Route::post('step-5', [RegisterController::class, 'storeStep5'])->name('step5.store');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

use App\Http\Controllers\UserController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ProfessionController;
use App\Http\Controllers\SevaController;
use App\Http\Controllers\ShikshaLevelController;
use App\Http\Controllers\BhaktiSadanController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\BloodGroupController;

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.show');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('users.index');
    })->name('dashboard');

    Route::post('/users/{user}/toggle-enabled', [UserController::class, 'toggleEnabled'])->name('users.toggle-enabled');
    Route::resource('users', UserController::class);
    Route::resource('educations', EducationController::class);
    Route::resource('professions', ProfessionController::class);
    Route::resource('sevas', SevaController::class);
    Route::resource('shiksha-levels', ShikshaLevelController::class);
    Route::resource('bhakti-sadans', BhaktiSadanController::class);
    Route::resource('languages', LanguageController::class);
    Route::resource('blood-groups', BloodGroupController::class);
});
