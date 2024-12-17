<?php

use App\Http\Controllers\CreateAsignatureController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\CreateDegreeController;
use App\Http\Controllers\CreateGroupController;
use App\Http\Controllers\CreateReferralController;
use App\Http\Controllers\PsicoController;
use App\Http\Middleware\PreventBackHistoryMiddleware;
use App\Http\Middleware\RoleDocenteMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\RolePsicoorientadorAndDocenteMiddleware;
use App\Http\Middleware\RolePsicoorientadorMiddleware;
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

        // ***************** Middleware Rol coordinador. *************************************
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

        // ***************************** Middleware rol docente **********************************
        Route::middleware([RoleDocenteMiddleware::class])->group(function() {

            // Vista de remision de estudiante
            Route::get('/create/referral', [CreateReferralController::class, 'create_referral'])->name('create.referral');
            // Store Referral, aquí tambien se crear al estudiante.
            Route::post('/store/referral', [CreateReferralController::class, 'store_referral'])->name('store.referral');
            // Index Students Remitted, Listar a los estudiantes remitidos.
            Route::get('/index/students/remitted', [CreateReferralController::class, 'index_student_remitted'])->name('index.student.remitted');
            // addMinutes, Visualiza a los estudiantes en piar pero tienen mas acciones.
            Route::get('/addMinutes', [CreateReferralController::class, 'addMinutes'])->name('addMinutes');
        });

        // ************** Middleware rol Docente y Psicoorientador, para desarrolar acciones que ambos tienen en común ***************
        Route::middleware([RolePsicoorientadorAndDocenteMiddleware::class])->group(function() {
            // Vista de editar estudiante
            Route::get('/edit/student/{id}', [CreateReferralController::class, 'edit_student'])->name('edit.student');
            // Update student
            Route::put('/update/student/{id}', [CreateReferralController::class, 'update_student'])->name('update.student');
        });


        // ******************************* Middleware rol Psicoorientador **************************
        Route::middleware([RolePsicoorientadorMiddleware::class])->group(function(){
            // Ruta para listar los estudiantes remitidos desde la interfaz de la psicologa
            Route::get('/index/students/remitted/psico', [PsicoController::class, 'index_student_remitted_psico'])->name('index.student.remitted.psico');
            // Ruta para visualizar el formulario para editar el motivo de la remisión
            Route::get('/details/referral/{id}', [PsicoController::class, 'detailsReferral'])->name('details.referral');
            // Ruta para editar/actualizar el estudiante y el motivo de remisión.
            Route::put('/edit/details/referral/{id}', [PsicoController::class, 'update_details_referral'])->name('update.details.referral');
            // Ruta para la vista de añadir informe(Motivo de la consulta)
            Route::get('/report/student/{id}', [PsicoController::class, 'report_student'])->name('report.student');
            // Ruta para agregar o crear el motivo de la consulta, a parte editara datos del estudiante si hace falta.
            Route::post('/store/report/student/{id}', [PsicoController::class, 'store_report_student'])->name('store.report.student');
            // Ruta para visualizar el historial del estudiante remitido. (Todas las remisiones e informes)
            Route::get('/student/history/{id}', [PsicoController::class, 'show_student_history'])->name('show.student.history');
            // Ruta para visualizar los detalles del historial de la remision del estudiante.
            Route::get('/history/details/referral/{id}', [PsicoController::class, 'history_details_referral'])->name('history.details.referral');
            // Ruta para visualizar los detalles del historial del informe del estudiante.
            Route::get('/history/details/report/{id}', [PsicoController::class, 'history_details_report'])->name('history.details.report');
            // Ruta para editar la remisión seleccionada en el historial.
            Route::put('/edit/history/details/referral/{id}', [PsicoController::class, 'update_history_details_referral'])->name('update.history.details.referral');
            // Ruta para editar el informe seleccionado en el historial.
            Route::put('/edit/history/details/report/{id}', [PsicoController::class, 'update_history_details_report'])->name('update.history.details.report');
            // Ruta para visualizar a los estudiantes en estado de espera. (Lo dirige a la vista.)
            Route::get('/waiting/students', [PsicoController::class, 'waiting_students'])->name('waiting.students');
            // Ruta para aceptar al estudiante en proceso PIAR
            Route::put('/accept/student', [PsicoController::class, 'accept_student_to_piar'])->name('accept.student.to.piar');
            // Ruta para descartar estudiante de proceso de espera. No es aceptado para el proceso PIAR.
            Route::put('/discard/student/{id}', [PsicoController::class, 'discard_student'])->name('discard.student');
        });

    });
        
});

