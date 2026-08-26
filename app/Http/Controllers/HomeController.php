<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use function Laravel\Prompts\select;
use App\Models\Modulo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index( Request $request)
    {
        // $usuario = auth()->user();
        // $rol = $usuario->rol;
        // $modulos = Modulo::where('rol', $rol)->where ('estatus', 'Activo')->get();



        // if ($request->has('modulo')) {
        //     $idModulo = $request->input('modulo');
        //     $moduloSeleccionado = Modulo::find($idModulo);

        //     if (!$moduloSeleccionado) {
        //         abort(404); // Manejar el caso en que no se encuentre el módulo
        //     }

        //     // Redirigir a la ruta correspondiente al módulo seleccionado
        //     return redirect()->route($moduloSeleccionado->enlace . '.index');
        // }

        return view('home');

    }


}
