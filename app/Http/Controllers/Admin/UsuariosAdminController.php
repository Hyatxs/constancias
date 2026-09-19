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
     * Lista todos los usuarios del sistema, sin importar su rol.
     * orderBy(...) evita que el orden cambie de forma rara entre recargas.
     * Si no hay usuarios todavía (base de datos recién migrada), $usuarios
     * simplemente viene vacío y la vista muestra un mensaje, sin romperse.
     */
    public function index()
    {
        $usuarios = Usuarios::orderBy('rol')->orderBy('nombre')->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

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
            'apellido_paterno' => ['nullable', 'string', 'max:50'],
            'apellido_materno' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', Rule::in(array_keys(Usuarios::allRoles()))],
            'matricula' => ['nullable', 'string', 'max:25'],
            'telefono' => ['nullable', 'string', 'max:15'],
        ]);

        Usuarios::create([
            'nombre' => $validated['nombre'],
            'apellido_paterno' => $validated['apellido_paterno'] ?? null,
            'apellido_materno' => $validated['apellido_materno'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol' => $validated['rol'],
            'matricula' => $validated['matricula'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('status', 'Usuario creado correctamente.');
    }
}