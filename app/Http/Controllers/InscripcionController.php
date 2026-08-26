<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Eventos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InscripcionController extends Controller
{
    public function index()
    {
        $eventos = Eventos::where('estatus', 'Aceptado')->get();
        return view('inscripciones.index', compact('eventos'));
    }

    public function create($id_evento)
    {
        $evento = Eventos::with('eventoHorario')->findOrFail($id_evento);
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

    
}
