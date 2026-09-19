<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * Deja pasar la petición solo si hay un usuario logueado con rol 'Administrador'.
     * Cualquier otro caso (no logueado, o logueado con otro rol) se corta aquí con un 403,
     * antes de que el request llegue al controller.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->rol !== 'Administrador') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
