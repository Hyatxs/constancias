<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Uso: ->middleware('role:Director,Coordinador,Administrador')
     * Deja pasar solo si el usuario logueado tiene uno de los roles listados.
     * Reemplaza tener que crear un middleware distinto para cada combinación de roles.
     */
    public function handle(Request $request, Closure $next, string ...$rolesPermitidos): Response
    {
        $usuario = $request->user();

        if (! $usuario || ! in_array($usuario->rol, $rolesPermitidos, true)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        return $next($request);
    }
}
