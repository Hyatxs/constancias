<?php

namespace App\Http\Controllers;

use App\Models\EventoHorario;
use App\Models\Eventos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoHorarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Coordinador,Director,Administrador')
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $horarios = EventoHorario::with('eventos')
            ->join('eventos', 'eventos_horarios.id_evento', '=', 'eventos.id_evento')
            ->orderBy('eventos.nombre_evento')
            ->select('eventos_horarios.*')
            ->get();

        return view('horarios.index', compact('horarios'));
    }

    /**
     * ANTES: si $id_evento venía vacío (por ejemplo, al entrar desde el menú genérico
     * "Horarios" -> "Crear"), esto redirigía de vuelta a eventos.index sin explicar nada.
     * AHORA: si no hay evento preseleccionado, se muestra el mismo formulario pero con
     * un <select> para elegir el evento manualmente, en vez de sacar al usuario de la página.
     */
    public function create($id_evento = null)
    {
        $eventos = Eventos::all();

        return view('horarios.create', compact('id_evento', 'eventos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_evento' => 'required|integer|exists:eventos,id_evento',
            'dia' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'ubicacion' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
        ]);
        try {
            $horario = EventoHorario::create($validated);

            return redirect()->route('eventos.show', ['evento' => $validated['id_evento']])
                             ->with('success', 'Horario asignado correctamente');
        } catch (\Exception $e) {
            return back()->withErrors('Error al crear el horario: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $horario = EventoHorario::findOrFail($id);
            $eventos = Eventos::all();
            return view('horarios.edit', compact('horario', 'eventos'));
        } catch (\Exception $e) {
            return back()->withErrors('Error al editar el horario: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'id_evento' => 'required|integer',
                'dia' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
                'ubicacion' => 'nullable|string',
                'observaciones' => 'nullable|string',
                'hora_inicio' => 'required',
                'hora_fin' => 'required',
            ]);

            $horario = EventoHorario::findOrFail($id);
            $horario->update($validated);

            return redirect()->route('horarios.index');
        } catch (\Exception $e) {
            return back()->withErrors('Error al actualizar el horario: ' . $e->getMessage());
        }
    }

    public function show(EventoHorario $horario)
    {
        $evento = Eventos::findOrFail($horario->id_evento);
        return view('horarios.show', compact('horario', 'evento'));
    }

    public function destroy(EventoHorario $horario)
    {
        $horario->delete();
        return redirect()->route('horarios.index')->with('success', 'Horario eliminado correctamente');
    }
}
