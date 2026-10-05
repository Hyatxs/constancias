<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Eventos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InscripcionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Solo el Estudiante se inscribe a eventos.
        $this->middleware('role:Estudiante')->only(['create', 'store']);
    }

    public function index()
    {
        // Solo se muestran eventos aprobados que todavía no empiezan.
        // whereDate compara solo la parte de la fecha, sin importar si la columna guarda hora.
        $eventos = Eventos::where('estatus', 'Aceptado')
            ->whereDate('fecha_inicio', '>', Carbon::today())
            ->get();

        // IDs de los eventos en los que este alumno ya está inscrito (para no ofrecerle el botón otra vez).
        $inscritos = Inscripcion::where('id_usuario', Auth::id())
            ->pluck('id_evento')
            ->all();

        return view('inscripciones.index', compact('eventos', 'inscritos'));
    }

    public function create($id_evento)
    {
        $evento = Eventos::with('eventoHorario')->findOrFail($id_evento);

        // Aunque el botón no aparezca, alguien podría escribir la URL a mano: se revisa aquí también.
        if ($motivo = $this->motivoInscripcionCerrada($evento)) {
            return redirect()->route('inscripciones.index')->with('error', $motivo);
        }

        if ($this->yaEstaInscrito($evento->id_evento)) {
            return redirect()->route('inscripciones.index')->with('error', 'Ya estás inscrito en este evento.');
        }

        return view('inscripciones.create', compact('evento'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|integer',
            'id_evento' => 'required|integer',
            'id_evento_horario' => 'required|integer',
            'estatus' => 'required|in:En proceso,Finalizado,Rechazado',
            'fecha_inscripcion' => 'required|date',
            'fecha_envio' => 'nullable|date',
        ]);

        $evento = Eventos::findOrFail($request->id_evento);

        if ($motivo = $this->motivoInscripcionCerrada($evento)) {
            return redirect()->route('inscripciones.index')->with('error', $motivo);
        }

        if ($this->yaEstaInscrito($evento->id_evento)) {
            return redirect()->route('inscripciones.index')->with('error', 'Ya estás inscrito en este evento.');
        }

        Inscripcion::create([
            'id_usuario' => Auth::id(),
            'id_evento' => $request->id_evento,
            'id_evento_horario' => $request->id_evento_horario,
            'estatus' => 'En proceso',
            'fecha_inscripcion' => now(),
            'fecha_envio' => null,
        ]);

        return redirect()->route('inscripciones.index')->with('success', 'Inscripción realizada exitosamente');
    }

    public function show($id)
    {
        // Encuentra la inscripción por ID
        $inscripcion = Inscripcion::with('evento')->findOrFail($id);

        // Pasa la inscripción y el evento a la vista
        return view('inscripciones.show', compact('inscripcion'));
    }

    /**
     * Devuelve el motivo por el que NO se puede inscribir a este evento, o null si sí se puede.
     * La inscripción cierra a las 00:00 del día en que empieza el evento.
     * (Para permitir inscribirse también el mismo día del evento hay que cambiar DOS cosas:
     *  aquí "lte" por "lt", y en index() el ">" de whereDate por ">=".)
     */
    private function motivoInscripcionCerrada(Eventos $evento): ?string
    {
        if ($evento->estatus !== 'Aceptado') {
            return 'Este evento todavía no está aprobado para inscripciones.';
        }

        if (Carbon::parse($evento->fecha_inicio)->startOfDay()->lte(Carbon::today())) {
            return 'Las inscripciones a este evento ya cerraron porque el evento ya inició.';
        }

        return null;
    }

    private function yaEstaInscrito($id_evento): bool
    {
        return Inscripcion::where('id_usuario', Auth::id())
            ->where('id_evento', $id_evento)
            ->exists();
    }
}