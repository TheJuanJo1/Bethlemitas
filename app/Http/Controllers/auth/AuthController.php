<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; 

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function logout() {
        Auth::logout();
        Session::flush();
        // Regenerar la sesión para prevenir el uso de la sesión antigua
        Session::regenerate(true);

        // Redirigir al login
        return redirect()->route('login');; // Puedes redirigir a la ruta de login
    }

    public function authenticate (Request $request) {
        $request->validate([
            'number_documment',
            'password',
        ]);

        if (Auth::attempt($request->only('number_documment', 'password'))) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['invalid_credentials' => 'Número de documento ó contraseña son incorrectas'])->withInput();
    }
}
