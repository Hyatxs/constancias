<?php

namespace App\Http\Controllers;

use App\Models\EventoHorario;


use App\Models\Eventos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoHorarioController extends Controller
{
    public function index()
    {

    // Obtener los horarios ordenados por el nombre del evento
    $horarios = EventoHorario::with('eventos')
    ->join('eventos', 'eventos_horarios.id_evento', '=', 'eventos.id_evento')
    ->orderBy('eventos.nombre_evento')
    ->select('eventos_horarios.*')
    ->get();

return view('horarios.index', compact('horarios'));
    }

    public function create($id_evento = null)
{
    if (!$id_evento) {
        return redirect()->route('eventos.index')->with('error', 'ID de evento no proporcionado');
    }

    // Obtener todos los eventos para opciones si es necesario
    $eventos = Eventos::all();

    return view('horarios.create', compact('id_evento', 'eventos'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_evento' => 'required|integer|exists:eventos,id_evento', // Validar id_evento correctamente
            'dia' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'ubicacion' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
        ]);
        try {
            // Crear el evento horario
            $horario = EventoHorario::create($validated);

            // Redireccionar según si se especificó id_evento en la solicitud
            if ($request->id_evento) {
                return redirect()->route('eventos.show', ['evento' => $request->id_evento])
                                 ->with('success', 'Horario asignado correctamente');
            } else {
                return redirect()->route('horarios.index')
                                 ->with('success', 'Horario creado correctamente');
            }
        } catch (\Exception $e) {
            // Manejar errores y redirigir de vuelta con mensaje de error
            return back()->withErrors('Error al crear el horario: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {

            $horario = EventoHorario::findOrFail($id);
            $eventos = Eventos::all(); // Obtener todos los eventos disponibles
            return view('horarios.edit', compact('horario', 'eventos'));
        } catch (\Exception $e) {
            dd($e);
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
            dd($e);
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

