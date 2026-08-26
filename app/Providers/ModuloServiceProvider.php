<?php

namespace App\Providers;

use App\Models\Modulo;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class ModuloServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {


        View::composer('*', function ($view) {
            $usuario = auth()->user();
            if (!$usuario) {
                return;
            }
            $rol = $usuario->rol;

            $modulos = Modulo::where('rol', $rol)->where ('estatus', 'Activo')-> orderBy('orden')-> get();

            $view->with('modulos', $modulos);
        });
    }
}
