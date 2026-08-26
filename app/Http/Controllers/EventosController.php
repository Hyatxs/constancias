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
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $eventos = Eventos::all();
        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        $directores = Usuarios::where('rol', 'Director')->get(); // Asume que tienes un campo 'rol' en la tabla de usuarios
        $tipos_eventos = TipoEvento::all();
        $modalidades = ['Virtual', 'Presencial'];
        $estatus = ['Aceptado', 'Pendiente', 'Rechazado'];

        return view('eventos.create', compact('directores', 'modalidades', 'estatus', 'tipos_eventos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tipo_evento' => 'required|integer|exists:tipos_eventos,id_tipo_evento',
            'id_director' => 'required|integer|exists:usuarios,id',
            'nombre_evento' => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'duracion_horas' => 'required|integer|min:1',
            'modalidad' => 'required|in:Virtual,Presencial',
            'folio' => 'required|string|max:5',
            'observaciones' => 'nullable|string',
            'academia' => 'required|string|max:100',
        ]);

        try {
            $evento = Eventos::create($validated + ['id_creador' => Auth::id()]);
            return redirect()->route('eventos.index')->with('success', 'Evento creado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors('Error al crear el evento: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $evento = Eventos::findOrFail($id);

        $horarios = EventoHorario::where('id_evento', $evento->id_evento)->get(); // Obtener los horarios asociados al evento

        $inscripcion = Inscripcion::where('id_usuario', Auth::id())
            ->where('id_evento', $evento->id_evento)
            ->first();

        return view('eventos.show', compact('evento', 'horarios'));
    }

    public function edit($id)
    {
        $evento = Eventos::findOrFail($id);
        $directores = Usuarios::where('rol', 'Director')->get();
        $tipos_eventos = TipoEvento::all();
        $modalidades = ['Virtual', 'Presencial'];
        $estatus = ['Aceptado', 'Pendiente', 'Rechazado'];

        return view('eventos.edit', compact('evento', 'directores', 'modalidades', 'estatus', 'tipos_eventos'));
    }

    public function update(Request $request, $id)
    {

        try {
            $validated = $request->validate([
                'nombre_evento' => 'required|string|max:100',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'descripcion' => 'nullable|string',
                'duracion_horas' => 'required|integer|min:1',
                'modalidad' => 'required|in:Virtual,Presencial',
                'folio' => 'required|string|max:5',
                'observaciones' => 'nullable|string',
                'academia' => 'required|string|max:100',
            ]);
            $evento = Eventos::findOrFail($id);


            $evento->update($validated);


            return redirect()->route('eventos.show', $evento->id_evento)->with('success', 'Evento actualizado exitosamente');
        } catch (\Exception $e) {
            dd($e);
            return back()->withErrors('Error al actualizar el evento: ' . $e->getMessage());
        }
    }

    public function indexDirector()
    {
        $eventos = Eventos::where('estatus', 'Pendiente')->get(); // Solo muestra eventos pendientes
        return view('eventos_director.index', compact('eventos'));
    }

    public function showDirector($id)
    {
        $evento = Eventos::findOrFail($id);
        $horarios = EventoHorario::where('id_evento', $evento->id_evento)->get(); // Obtener los horarios asociados al evento

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
        $eventos = Eventos::whereIn('estatus', ['Aceptado', 'Rechazado'])->get(); // Solo muestra eventos aceptados o rechazados
        return view('historial_director.index', compact('eventos'));
    }



    public function destroy(Eventos $evento)
    {
        $evento->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento eliminado exitosamente');
    }




}


