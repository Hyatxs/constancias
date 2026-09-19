<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuariosAdminController extends Controller
{
    /**
     * Muestra el formulario para crear un usuario con cualquier rol.
     * Ya llega protegido por el middleware 'admin' (ver routes/web.php),
     * así que aquí no hace falta revisar el rol otra vez.
     */
    public function create()
{
    return view('admin.usuarios.create');
}

    /**
     * Valida y crea el usuario nuevo.
     *
     * La diferencia clave con RegisterController::validator() es que aquí SÍ se permite
     * elegir cualquiera de los 5 roles, porque quien manda este formulario ya se verificó
     * que es Administrador (por el middleware). Rule::in() rechaza cualquier valor que no
     * esté en la lista, exactamente igual que le falta al registro público.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', Rule::in(array_keys(Usuarios::allRoles()))],
            'matricula' => ['required', 'string', 'max:50'],
            'telefono' => ['required', 'string', 'max:15'],
        ]);

        Usuarios::create([
            'nombre' => $validated['nombre'],
            'apellido_paterno' => $validated['apellido_paterno'],
            'apellido_materno' => $validated['apellido_materno'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol' => $validated['rol'],
            'matricula' => $validated['matricula'],
            'telefono' => $validated['telefono'],
        ]);

       return redirect()->route('admin.usuarios.index')
    ->with('status', 'Usuario creado correctamente.');
    }
}
