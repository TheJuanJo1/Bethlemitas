<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function logout() {
        Auth::logout();
        Session::flush();
        Session::regenerate(true);

        return redirect()->route('login');
    }

    public function authenticate(Request $request) {
        $request->validate([
            'number_documment' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('number_documment', 'password'))) {
            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors(['invalid_credentials' => 'Número de documento ó contraseña son incorrectas'])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | OLVIDÉ MI CONTRASEÑA
    |--------------------------------------------------------------------------
    */

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Te hemos enviado un enlace de recuperación a tu correo')
            : back()->withErrors(['email' => 'No se encontró un usuario con ese correo']);
    }

    /*
    |--------------------------------------------------------------------------
    | NUEVA CONTRASEÑA
    |--------------------------------------------------------------------------
    */

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
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
    }
}