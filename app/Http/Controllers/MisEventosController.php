<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Inscripcion;
use App\Models\Eventos;
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

        return view('mis_eventos.index', compact('inscripciones_en_proceso', 'inscripciones_finalizadas'));
    }



    public function descargarConstancia($id_evento)
    {
        $evento = Eventos::findOrFail($id_evento);

        $inscripcion = Inscripcion::where('id_evento', $id_evento)
                                  ->where('id_usuario', Auth::id())
                                  ->first();

        if (!$inscripcion) {
            return redirect()->route('mis_eventos.index')->with('error', 'No estás inscrito en este evento.');
        }

        $data = [
            'nombre_evento' => $evento->nombre_evento,
            'fecha_inicio' => $evento->fecha_inicio,
            'fecha_fin' => $evento->fecha_fin,
            'nombre' => Auth::user()->name,
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
        // Obtén el evento y los datos necesarios
        $evento = Eventos::findOrFail($eventoId);
        $usuario = Auth::user();


        $data = [
            'nombre_evento' => $evento->nombre_evento,
            'fecha_inicio' => $evento->fecha_inicio,
            'fecha_fin' => $evento->fecha_fin,
            'nombre' => Auth::user()->name,
            'folio' => $evento->folio,
            'duracion_horas' => $evento->duracion_horas,
            'modalidad' => $evento->modalidad,
            // Otros datos relevantes
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
        $evento = Eventos::findOrFail($eventoId);
        $data = [
            'nombre_evento' => $evento->nombre_evento,
            'fecha_inicio' => $evento->fecha_inicio,
            'fecha_fin' => $evento->fecha_fin,
            'nombre' => Auth::user()->name, // Asegúrate de que Auth está configurado
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
}
