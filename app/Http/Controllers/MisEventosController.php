<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Inscripcion;
use App\Models\Eventos;
use App\Models\Evidencias;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Usuarios;

use PDF;

class MisEventosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario_id = Auth::id();
        $inscripciones = Inscripcion::with('evento')
            ->where('id_usuario', $usuario_id)
            ->get();

        $now = Carbon::now();

        foreach ($inscripciones as $inscripcion) {
            if ($inscripcion->evento && $inscripcion->evento->fecha_fin < $now && $inscripcion->estatus !== 'Finalizado') {
                $inscripcion->estatus = 'Finalizado';
                $inscripcion->save();
            }
        }

        $inscripciones_en_proceso = $inscripciones->filter(function ($inscripcion) {
            return $inscripcion->estatus === 'En proceso';
        });

        $inscripciones_finalizadas = $inscripciones->filter(function ($inscripcion) {
            return $inscripcion->estatus === 'Finalizado';
        });

        // Para cada inscripción finalizada, se calcula si la constancia ya está disponible
        // y, si no, por qué no — así la vista puede mostrarlo sin volver a consultar nada.
        foreach ($inscripciones_finalizadas as $inscripcion) {
            $inscripcion->motivo_constancia_bloqueada = $this->motivoConstanciaBloqueada($inscripcion);
        }

        return view('mis_eventos.index', compact('inscripciones_en_proceso', 'inscripciones_finalizadas'));
    }



    public function descargarConstancia($id_evento)
    {
        $inscripcion = $this->inscripcionDelUsuario($id_evento);

        if (!$inscripcion) {
            return redirect()->route('mis_eventos.index')->with('error', 'No estás inscrito en este evento.');
        }

        if ($motivo = $this->motivoConstanciaBloqueada($inscripcion)) {
            return redirect()->route('mis_eventos.index')->with('error', $motivo);
        }

        $evento = $inscripcion->evento;
        $usuario = Auth::user();

        $data = [
            'nombre_evento' => $evento->nombre_evento,
            'fecha_inicio' => $evento->fecha_inicio,
            'fecha_fin' => $evento->fecha_fin,
            'nombre' => trim("{$usuario->nombre} {$usuario->apellido_paterno} {$usuario->apellido_materno}"),
            'folio' => $evento->folio,
            'duracion_horas' => $evento->duracion_horas,
            'modalidad' => $evento->modalidad,
            // Otros datos relevantes
        ];

        $pdf = PDF::loadView('eventos.constancia', $data);

        return $pdf->download('constancia_evento_' . $evento->id_evento . '.pdf');
    }
    public function verConstancia($eventoId)
    {
        $inscripcion = $this->inscripcionDelUsuario($eventoId);

        if (!$inscripcion) {
            return redirect()->route('mis_eventos.index')->with('error', 'No estás inscrito en este evento.');
        }

        if ($motivo = $this->motivoConstanciaBloqueada($inscripcion)) {
            return redirect()->route('mis_eventos.index')->with('error', $motivo);
        }

        $evento = $inscripcion->evento;
        $usuario = Auth::user();

        $data = [
            'nombre_evento' => $evento->nombre_evento,
            'fecha_inicio' => $evento->fecha_inicio,
            'fecha_fin' => $evento->fecha_fin,
            'folio' => $evento->folio,
            'duracion_horas' => $evento->duracion_horas,
            'modalidad' => $evento->modalidad,
            'nombre' => $usuario->nombre,
            'apellido_paterno' => $usuario->apellido_paterno,
            'apellido_materno' => $usuario->apellido_materno,
        ];

        // Genera el PDF
        $pdf = PDF::loadView('templates.constancia.constancia', $data)->setPaper('a4','landscape');

        // Mostrar el PDF en el navegador
        return $pdf->stream('constancia_evento_' . '.pdf');
    }


    public function generarConstancia($eventoId)
    {
        $inscripcion = $this->inscripcionDelUsuario($eventoId);

        if (!$inscripcion) {
            return redirect()->route('mis_eventos.index')->with('error', 'No estás inscrito en este evento.');
        }

        if ($motivo = $this->motivoConstanciaBloqueada($inscripcion)) {
            return redirect()->route('mis_eventos.index')->with('error', $motivo);
        }

        $evento = $inscripcion->evento;
        $usuario = Auth::user();

        $data = [
            'nombre_evento' => $evento->nombre_evento,
            'fecha_inicio' => $evento->fecha_inicio,
            'fecha_fin' => $evento->fecha_fin,
            'nombre' => trim("{$usuario->nombre} {$usuario->apellido_paterno} {$usuario->apellido_materno}"),
            'folio' => $evento->folio,
            'duracion_horas' => $evento->duracion_horas,
            'modalidad' => $evento->modalidad,
            // Otros datos relevantes
        ];

        $pdf = PDF::loadView('eventos.constancia', $data);

        $fileName = 'constancia_evento_' . $evento->id_evento . '.pdf';
        $filePath = storage_path('app/public/constancias/' . $fileName);

        // Asegúrate de que la carpeta existe antes de intentar guardar el archivo
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        $pdf->save($filePath);

        return response()->download($filePath);
    }

    private function inscripcionDelUsuario($id_evento): ?Inscripcion
    {
        return Inscripcion::with('evento')
            ->where('id_evento', $id_evento)
            ->where('id_usuario', Auth::id())
            ->first();
    }

    /**
     * Devuelve el motivo por el que la constancia NO está disponible todavía, o null
     * si ya se puede ver/descargar. Se necesitan las dos cosas:
     *   1) La evidencia del evento fue Aprobada por el Director asignado.
     *   2) El propio alumno fue marcado como "Asistió" por el Maestro asignado.
     */
    private function motivoConstanciaBloqueada(Inscripcion $inscripcion): ?string
    {
        $evidenciaAprobada = Evidencias::where('id_evento', $inscripcion->id_evento)
            ->where('estatus', 'Aprobada')
            ->exists();

        if (!$evidenciaAprobada) {
            return 'Tu constancia todavía no está disponible: el Director aún no aprueba la evidencia de este evento.';
        }

        if ($inscripcion->asistencia !== 'Asistió') {
            return 'Tu constancia todavía no está disponible: tu profesor aún no confirma tu asistencia a este evento.';
        }

        return null;
    }
}