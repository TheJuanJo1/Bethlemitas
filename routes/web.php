<?php

use App\Http\Middleware\PreventBackHistoryMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;

// Rutas para la autenticación
Route::prefix('/')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware([PreventBackHistoryMiddleware::class])->group(function() {
    Route::middleware(['auth'])->group(function() {
        Route::get('/dashboard', function () {
            return view('layout.homePage');
        })->name('dashboard');
    });
});

