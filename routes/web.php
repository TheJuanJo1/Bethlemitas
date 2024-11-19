<?php

use App\Http\Controllers\CreateAsignatureController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\CreateDegreeController;
use App\Http\Controllers\CreateGroupController;
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
            // Vista create group
            Route::get('/create/group', [CreateGroupController::class, 'create_group'])->name('create.group');
            // Store Group
            Route::post('/store/group', [CreateGroupController::class, 'store_group'])->name('store.group');
            // Update Group
            Route::put('/update/group', [CreateGroupController::class, 'update_group'])->name('update.group');
            // Delete Group
            Route::delete('/delete/group/{id}', [CreateGroupController::class, 'destroy_group'])->name('destroy.group');
            // Vista create degree
            Route::get('/create/degree', [CreateDegreeController::class, 'create_degree'])->name('create.degree');
            // Store Degree
            Route::post('/store/degree', [CreateDegreeController::class, 'store_degree'])->name('store.degree');
            // Update Degree
            Route::put('/update/degree', [CreateDegreeController::class, 'update_degree'])->name('update.degree');
            // Delete Degree
            Route::delete('/delete/degree/{id}', [CreateDegreeController::class, 'destroy_degree'])->name('delete.degree');
            // Vista create Asignature
            Route::get('/create/asignature', [CreateAsignatureController::class, 'create_asignature'])->name('create.asignature');
            // Store Asignature
            Route::post('/store/asignature', [CreateAsignatureController::class, 'store_asignature'])->name('store.asignature');
            // Update Asignature
            Route::put('/update/asignature', [CreateAsignatureController::class, 'update_asignature'])->name('update.asignature');
            // Delete Asignature
            Route::delete('/delete/asignature/{id}', [CreateAsignatureController::class, 'destroy_asignature'])->name('delete.asignature');
        });

    });
        
});

