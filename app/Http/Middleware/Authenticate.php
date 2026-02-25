<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, \Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        $user = Auth::user();

        // 🔥 Verificar si está suspendido
        if ($user && $user->id_state == 2) {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors([
                    'blocked' => 'Tu cuenta se encuentra suspendida. Comunícate con el Director.'
                ]);
        }

        return $next($request);
    }
}