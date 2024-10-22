<?php

use App\Http\Controllers\CreateController;
use App\Http\Middleware\PreventBackHistoryMiddleware;
use App\Http\Middleware\RoleMiddleware;
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

        //Middleware Rol coordinador.
        Route::middleware([RoleMiddleware::class])->group(function() { 
            
            //Vista create user
            Route::get('/create/user', [CreateController::class, 'create_user'])->name('create.user');  
            // Store user
            Route::post('/store/user', [CreateController::class, 'store_user'])->name('store.user');
            //Index Users (docentes y psicoorientadores)
            Route::get('/listing/users', [CreateController::class, 'index_users'])->name('index.users');  
            // Edit User (Vista)
            Route::get('/edit/{id}/user', [CreateController::class, 'edit_user'])->name('edit.user');      
            // Update User
            Route::put('/update/user/{id}', [CreateController::class, 'update_user'])->name('update.user');   
            // Delete User (No lo estoy eliminando sino que cambio su estado a bloqueado)
            Route::put('/delete/user/{id}', [CreateController::class, 'destroy_user'])->name('destroy.user');  
        });

    });
        
});

