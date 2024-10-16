<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{

    //Middleware para el rol Coordinador.
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check() || !Auth::user()->hasRole('coordinador')) {
            // Si el usuario no está autenticado o su rol no está permitido, redirigir o lanzar un error
            abort(403, 'No tienes permiso para acceder a esta página.'); // o puedes lanzar un 403
        }

        return $next($request);
    }
}
