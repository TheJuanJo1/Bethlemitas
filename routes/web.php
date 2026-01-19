<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\CreateAreaController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\CreateDegreeController;
use App\Http\Controllers\CreateGroupController;
use App\Http\Controllers\CreateReferralController;
use App\Http\Controllers\PsicoController;

use App\Http\Middleware\PreventBackHistoryMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\RoleDocenteMiddleware;
use App\Http\Middleware\RolePsicoorientadorMiddleware;
use App\Http\Middleware\RolePsicoorientadorAndDocenteMiddleware;

/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::prefix('/')->group(function () {

    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| OLVIDÉ MI CONTRASEÑA
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {

    $request->validate([
        'email' => 'required|email'
    ]);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', 'Te hemos enviado un enlace de recuperación a tu correo')
        : back()->withErrors(['email' => 'No se encontró un usuario con ese correo']);
})->name('password.email');

/*
|--------------------------------------------------------------------------
| NUEVA CONTRASEÑA
|--------------------------------------------------------------------------
*/

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {

    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', 'Contraseña actualizada correctamente')
        : back()->withErrors(['email' => 'El enlace es inválido o ya expiró']);
})->name('password.update');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware([PreventBackHistoryMiddleware::class])->group(function () {

    Route::middleware(['auth'])->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return view('layout.homePage');
        })->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | ROL COORDINADOR
        |--------------------------------------------------------------------------
        */

        Route::middleware([RoleMiddleware::class])->group(function () {

            Route::get('/create/user', [CreateController::class, 'create_user'])->name('create.user');
            Route::post('/store/user', [CreateController::class, 'store_user'])->name('store.user');
            Route::get('/listing/users', [CreateController::class, 'index_users'])->name('index.users');
            Route::get('/edit/{id}/user', [CreateController::class, 'edit_user'])->name('edit.user');
            Route::put('/update/user/{id}', [CreateController::class, 'update_user'])->name('update.user');
            Route::put('/delete/user/{id}', [CreateController::class, 'destroy_user'])->name('destroy.user');

            Route::get('/create/group', [CreateGroupController::class, 'create_group'])->name('create.group');
            Route::post('/store/group', [CreateGroupController::class, 'store_group'])->name('store.group');
            Route::put('/update/group', [CreateGroupController::class, 'update_group'])->name('update.group');
            Route::delete('/delete/group/{id}', [CreateGroupController::class, 'destroy_group'])->name('destroy.group');

            Route::get('/create/degree', [CreateDegreeController::class, 'create_degree'])->name('create.degree');
            Route::post('/store/degree', [CreateDegreeController::class, 'store_degree'])->name('store.degree');
            Route::put('/update/degree', [CreateDegreeController::class, 'update_degree'])->name('update.degree');
            Route::delete('/delete/degree/{id}', [CreateDegreeController::class, 'destroy_degree'])->name('delete.degree');

            Route::get('/create/area', [CreateAreaController::class, 'create_area'])->name('create.area');
            Route::post('/store/area', [CreateAreaController::class, 'store_area'])->name('store.area');
            Route::put('/update/area', [CreateAreaController::class, 'update_area'])->name('update.area');
            Route::delete('/delete/area/{id}', [CreateAreaController::class, 'destroy_area'])->name('delete.area');
        });

        /*
        |--------------------------------------------------------------------------
        | ROL DOCENTE
        |--------------------------------------------------------------------------
        */

        Route::middleware([RoleDocenteMiddleware::class])->group(function () {

            Route::get('/index/students/remitted', [CreateReferralController::class, 'index_student_remitted'])
                ->name('index.student.remitted');

            Route::get('/addMinutes', [CreateReferralController::class, 'addMinutes'])
                ->name('addMinutes');
        });

        /*
        |--------------------------------------------------------------------------
        | ROL DOCENTE Y PSICOORIENTADOR
        |--------------------------------------------------------------------------
        */

        Route::middleware([RolePsicoorientadorAndDocenteMiddleware::class])->group(function () {

            Route::get('/create/referral', [CreateReferralController::class, 'create_referral'])
                ->name('create.referral');

            Route::post('/store/referral', [CreateReferralController::class, 'store_referral'])
                ->name('store.referral');

            Route::get('/edit/student/{id}', [CreateReferralController::class, 'edit_student'])
                ->name('edit.student');

            Route::put('/update/student/{id}', [CreateReferralController::class, 'update_student'])
                ->name('update.student');
        });

        /*
        |--------------------------------------------------------------------------
        | ROL PSICOORIENTADOR
        |--------------------------------------------------------------------------
        */

        Route::middleware([RolePsicoorientadorMiddleware::class])->group(function () {

            Route::get('/index/students/remitted/psico', [PsicoController::class, 'index_student_remitted_psico'])
                ->name('index.student.remitted.psico');

            // ✅ ESTUDIANTES POR ESTADO
            Route::get('/psico/students/active', [PsicoController::class, 'index_students_active_psico'])
                ->name('psico.students.active');

            Route::get('/psico/students/piar', [PsicoController::class, 'index_students_piar_psico'])
                ->name('psico.students.piar');

            Route::get('/psico/students/dua', [PsicoController::class, 'index_students_dua_psico'])
                ->name('psico.students.dua');

            Route::get('/details/referral/{id}', [PsicoController::class, 'detailsReferral'])
                ->name('details.referral');

            Route::put('/edit/details/referral/{id}', [PsicoController::class, 'update_details_referral'])
                ->name('update.details.referral');

            Route::get('/report/student/{id}', [PsicoController::class, 'report_student'])
                ->name('report.student');

            Route::post('/store/report/student/{id}', [PsicoController::class, 'store_report_student'])
                ->name('store.report.student');

            Route::get('/student/history/{id}', [PsicoController::class, 'show_student_history'])
                ->name('show.student.history');

            Route::get('/history/details/referral/{id}', [PsicoController::class, 'history_details_referral'])
                ->name('history.details.referral');

            Route::get('/history/details/report/{id}', [PsicoController::class, 'history_details_report'])
                ->name('history.details.report');

            Route::put('/edit/history/details/referral/{id}', [PsicoController::class, 'update_history_details_referral'])
                ->name('update.history.details.referral');

            Route::put('/edit/history/details/report/{id}', [PsicoController::class, 'update_history_details_report'])
                ->name('update.history.details.report');

            Route::put('/accept/student', [PsicoController::class, 'accept_student_to_piar'])
                ->name('accept.student.to.piar');
        });
    });
});
