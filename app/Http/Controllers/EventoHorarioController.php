<?php

namespace App\Http\Controllers;

use App\Models\EventoHorario;
use App\Models\Eventos;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventoHorarioController extends Controller
{
    // Equivalencia entre el nombre del día y el número que usa Carbon (0 = domingo ... 6 = sábado)
    private const DIAS = [
        'Domingo' => 0,
        'Lunes' => 1,
        'Martes' => 2,
        'Miércoles' => 3,
        'Jueves' => 4,
        'Viernes' => 5,
        'Sábado' => 6,
    ];

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
     * Si no hay evento preseleccionado, el formulario muestra un <select>
     * para elegirlo en vez de redirigir fuera de la página.
     */
    public function create($id_evento = null)
    {
        $eventos = Eventos::all();

        return view('horarios.create', compact('id_evento', 'eventos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->reglas(), $this->mensajes());

        $evento = Eventos::findOrFail($validated['id_evento']);

        if ($error = $this->errorDiaFueraDeRango($evento, $validated['dia'])) {
            return back()->withInput()->withErrors(['dia' => $error]);
        }

        try {
            EventoHorario::create($validated);

            return redirect()->route('eventos.show', ['evento' => $validated['id_evento']])
                             ->with('success', 'Horario asignado correctamente');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Error al crear el horario: ' . $e->getMessage());
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
        // La validación va FUERA del try/catch: si va dentro, el catch se traga los
        // errores de validación y solo se ve un mensaje genérico.
        $validated = $request->validate($this->reglas(), $this->mensajes());

        $evento = Eventos::findOrFail($validated['id_evento']);

        if ($error = $this->errorDiaFueraDeRango($evento, $validated['dia'])) {
            return back()->withInput()->withErrors(['dia' => $error]);
        }

        try {
            $horario = EventoHorario::findOrFail($id);
            $horario->update($validated);

            return redirect()->route('horarios.index');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Error al actualizar el horario: ' . $e->getMessage());
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

    /**
     * Reglas compartidas por store() y update().
     * 'after:hora_inicio' compara contra el otro campo: hora_fin debe ser posterior.
     * 'H:i,H:i:s' acepta la hora con o sin segundos (la BD puede devolverla con segundos).
     */
    private function reglas(): array
    {
        return [
            'id_evento' => 'required|integer|exists:eventos,id_evento',
            'dia' => 'required|in:' . implode(',', array_keys(self::DIAS)),
            'ubicacion' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'hora_inicio' => 'required|date_format:H:i,H:i:s',
            'hora_fin' => 'required|date_format:H:i,H:i:s|after:hora_inicio',
        ];
    }

    private function mensajes(): array
    {
        return [
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ];
    }

    /**
     * Devuelve los días de la semana que ocurren al menos una vez entre
     * fecha_inicio y fecha_fin del evento. Se revisan máximo 7 días: pasada
     * una semana completa ya aparecieron todos los días posibles.
     */
    private function diasValidosDelEvento(Eventos $evento): array
    {
        $inicio = Carbon::parse($evento->fecha_inicio)->startOfDay();
        $fin = Carbon::parse($evento->fecha_fin)->startOfDay();

        $validos = [];
        $fecha = $inicio->copy();

        for ($i = 0; $i < 7 && $fecha->lte($fin); $i++) {
            $validos[] = array_search($fecha->dayOfWeek, self::DIAS, true);
            $fecha->addDay();
        }

        return $validos;
    }

    /**
     * Devuelve un mensaje de error si el día elegido no cae dentro del rango
     * del evento, o null si todo está bien.
     */
    private function errorDiaFueraDeRango(Eventos $evento, string $dia): ?string
    {
        $validos = $this->diasValidosDelEvento($evento);

        if (in_array($dia, $validos, true)) {
            return null;
        }

        $inicio = Carbon::parse($evento->fecha_inicio)->format('d/m/Y');
        $fin = Carbon::parse($evento->fecha_fin)->format('d/m/Y');

        return "El evento va del {$inicio} al {$fin}, así que solo puedes elegir: " . implode(', ', $validos) . '.';
    }
}