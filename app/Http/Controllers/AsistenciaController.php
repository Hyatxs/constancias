<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsistenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Maestro');
    }

    /**
     * Lista los eventos del Maestro que ya pasaron (tiene sentido tomar asistencia
     * hasta que el evento haya ocurrido, no antes).
     */
    public function index()
    {
        $eventos = Eventos::where('id_maestro', Auth::id())
            ->whereDate('fecha_fin', '<=', Carbon::today())
            ->withCount('inscripciones')
            ->orderByDesc('fecha_fin')
            ->get();

        return view('asistencia.index', compact('eventos'));
    }

    /**
     * Lista los alumnos inscritos a un evento específico, con botones para marcar
     * Asistió / No asistió por cada uno.
     */
    public function show($id_evento)
    {
        $evento = Eventos::findOrFail($id_evento);

        if ((int) $evento->id_maestro !== (int) Auth::id()) {
            abort(403, 'Este no es uno de tus eventos asignados.');
        }

        $inscripciones = Inscripcion::with('usuario')
            ->where('id_evento', $id_evento)
            ->get();

        return view('asistencia.show', compact('evento', 'inscripciones'));
    }

    public function marcar(Request $request, $id_inscripcion)
    {
        $request->validate([
            'asistencia' => 'required|in:Asistió,No asistió',
        ]);

        $inscripcion = Inscripcion::findOrFail($id_inscripcion);
        $evento = Eventos::findOrFail($inscripcion->id_evento);

        if ((int) $evento->id_maestro !== (int) Auth::id()) {
            abort(403, 'Este no es uno de tus eventos asignados.');
        }

        $inscripcion->update(['asistencia' => $request->asistencia]);

        return back()->with('success', 'Asistencia actualizada.');
    }
}