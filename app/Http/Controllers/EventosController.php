<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Eventos;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\TipoEvento;
use App\Models\EventoHorario;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Log;

class EventosController extends Controller
{
    // Si se edita alguno de estos campos en un evento ya Aceptado, hay que
    // volver a pedirle al Director que lo revise, porque son los datos que él aprobó.
    private const CAMPOS_QUE_REQUIEREN_NUEVA_APROBACION = [
        'nombre_evento', 'fecha_inicio', 'fecha_fin', 'duracion_horas', 'modalidad',
    ];

    public function __construct()
    {
        $this->middleware('auth');

        // Solo estos roles pueden crear, editar o borrar eventos.
        // index() y show() se quedan abiertos a cualquier usuario logueado (todos pueden ver).
        $this->middleware('role:Coordinador,Director,Administrador')
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        // Un Maestro solo debe ver los eventos que tiene asignados.
        // Coordinador/Director/Administrador siguen viendo todos, para poder gestionarlos.
        $eventos = Auth::user()->rol === 'Maestro'
            ? Eventos::where('id_maestro', Auth::id())->get()
            : Eventos::all();

        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        $directores = Usuarios::where('rol', 'Director')->get();
        $maestros = Usuarios::where('rol', 'Maestro')->get();
        $tipos_eventos = TipoEvento::all();
        $modalidades = ['Virtual', 'Presencial'];
        $estatus = ['Aceptado', 'Pendiente', 'Rechazado'];

        return view('eventos.create', compact('directores', 'maestros', 'modalidades', 'estatus', 'tipos_eventos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tipo_evento' => 'required|integer|exists:tipos_eventos,id_tipo_evento',
            'id_director' => 'required|integer|exists:usuarios,id',
            'id_maestro' => 'required|integer|exists:usuarios,id',
            'nombre_evento' => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'duracion_horas' => 'required|integer|min:1',
            'modalidad' => 'required|in:Virtual,Presencial',
            'folio' => 'required|string|max:5',
            'observaciones' => 'nullable|string',
            'academia' => 'required|string|max:100',
        ], [
            'id_maestro.required' => 'No puedes avanzar sin asignar un profesor a este evento.',
        ]);

        try {
            $evento = Eventos::create($validated + ['id_creador' => Auth::id()]);
            return redirect()->route('eventos.index')->with('success', 'Evento creado exitosamente');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Error al crear el evento: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $evento = Eventos::findOrFail($id);
        $horarios = EventoHorario::where('id_evento', $evento->id_evento)->get();

        $inscripcion = Inscripcion::where('id_usuario', Auth::id())
            ->where('id_evento', $evento->id_evento)
            ->first();

        return view('eventos.show', compact('evento', 'horarios', 'inscripcion'));
    }

    public function edit($id)
    {
        $evento = Eventos::findOrFail($id);
        $directores = Usuarios::where('rol', 'Director')->get();
        $maestros = Usuarios::where('rol', 'Maestro')->get();
        $tipos_eventos = TipoEvento::all();
        $modalidades = ['Virtual', 'Presencial'];
        $estatus = ['Aceptado', 'Pendiente', 'Rechazado'];

        return view('eventos.edit', compact('evento', 'directores', 'maestros', 'modalidades', 'estatus', 'tipos_eventos'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nombre_evento' => 'required|string|max:100',
                'id_maestro' => 'required|integer|exists:usuarios,id',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'descripcion' => 'nullable|string',
                'duracion_horas' => 'required|integer|min:1',
                'modalidad' => 'required|in:Virtual,Presencial',
                'folio' => 'required|string|max:5',
                'observaciones' => 'nullable|string',
                'academia' => 'required|string|max:100',
            ], [
                'id_maestro.required' => 'No puedes avanzar sin asignar un profesor a este evento.',
            ]);
            $evento = Eventos::findOrFail($id);

            // Si el evento ya estaba Aceptado, alguien distinto al Director asignado
            // está editando, y cambió algo que el Director había aprobado, regresa a Pendiente.
            if (
                $evento->estatus === 'Aceptado'
                && Auth::id() !== $evento->id_director
                && $this->cambioAlgoQueRequiereNuevaAprobacion($evento, $validated)
            ) {
                $validated['estatus'] = 'Pendiente';
            }

            $evento->update($validated);

            $mensaje = ($validated['estatus'] ?? null) === 'Pendiente'
                ? 'Evento actualizado. Como se modificaron datos ya aprobados, el evento vuelve a esperar la revisión del Director.'
                : 'Evento actualizado exitosamente';

            return redirect()->route('eventos.show', $evento->id_evento)->with('success', $mensaje);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Error al actualizar el evento: ' . $e->getMessage());
        }
    }

    public function indexDirector()
    {
        $eventos = Eventos::where('estatus', 'Pendiente')->get();
        return view('eventos_director.index', compact('eventos'));
    }

    public function showDirector($id)
    {
        $evento = Eventos::findOrFail($id);
        $horarios = EventoHorario::where('id_evento', $evento->id_evento)->get();

        return view('eventos_director.show', compact('evento', 'horarios'));
    }

    public function aceptar($id)
    {
        $evento = Eventos::findOrFail($id);
        $evento->estatus = 'Aceptado';
        $evento->save();

        return redirect()->route('eventos-director.show', $evento->id_evento)->with('success', 'Evento aceptado exitosamente');
    }

    public function rechazar($id)
    {
        $evento = Eventos::findOrFail($id);
        $evento->estatus = 'Rechazado';
        $evento->save();

        return redirect()->route('eventos-director.show', $evento->id_evento)->with('success', 'Evento rechazado exitosamente');
    }

    public function historialDirector()
    {
        $eventos = Eventos::whereIn('estatus', ['Aceptado', 'Rechazado'])->get();
        return view('historial_director.index', compact('eventos'));
    }

    public function destroy(Eventos $evento)
    {
        $evento->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento eliminado exitosamente');
    }

    private function cambioAlgoQueRequiereNuevaAprobacion(Eventos $evento, array $validated): bool
    {
        foreach (self::CAMPOS_QUE_REQUIEREN_NUEVA_APROBACION as $campo) {
            // (string) evita falsos positivos por comparar tipos distintos (ej. "1" vs 1).
            if ((string) $evento->{$campo} !== (string) ($validated[$campo] ?? $evento->{$campo})) {
                return true;
            }
        }

        return false;
    }
}