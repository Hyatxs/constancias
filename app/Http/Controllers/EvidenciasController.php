<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evidencias;
use Illuminate\Support\Facades\Storage;
use App\Models\Eventos;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EvidenciasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Solo el Maestro sube/edita/borra evidencia: es quien impartió el evento.
        // Coordinador/Director/Administrador ya no gestionan evidencia ajena, solo la consultan.
        $this->middleware('role:Maestro')->only(['create', 'store', 'edit', 'update', 'destroy']);
        // Solo el Director (el asignado al evento específico, se revisa dentro del método) aprueba/rechaza.
        $this->middleware('role:Director')->only(['aprobar', 'rechazar']);
    }

    public function index()
    {
        if (Auth::user()->rol === 'Maestro') {
            $idsEventosPropios = Eventos::where('id_maestro', Auth::id())->pluck('id_evento');
            $evidencias = Evidencias::whereIn('id_evento', $idsEventosPropios)->get();
        } elseif (Auth::user()->rol === 'Director') {
            // El Director solo revisa evidencia de SUS eventos asignados.
            $idsEventosPropios = Eventos::where('id_director', Auth::id())->pluck('id_evento');
            $evidencias = Evidencias::whereIn('id_evento', $idsEventosPropios)->get();
        } else {
            // Coordinador/Administrador ven todas, para supervisión general.
            $evidencias = Evidencias::all();
        }

        return view('evidencias.index', compact('evidencias'));
    }

    public function create()
    {
        // Ya solo un Maestro llega aquí (por el middleware del constructor), así que
        // siempre se le muestran nada más los eventos que tiene asignados.
        $eventos = Eventos::where('id_maestro', Auth::id())->get();

        return view('evidencias.create', compact('eventos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_evento' => 'required|exists:eventos,id_evento',
            'archivo' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Aunque el <select> del formulario ya solo le muestre sus eventos, esto evita que
        // alguien suba evidencia de un evento ajeno mandando la petición manualmente.
        if ($motivo = $this->motivoSinPermiso($request->id_evento)) {
            return back()->withErrors($motivo);
        }

        // Un evento solo puede tener UNA evidencia "activa" (Pendiente o Aprobada) a la vez.
        // Si la única que existe fue Rechazada, se reemplaza por la nueva en vez de acumular filas.
        $evidenciaExistente = Evidencias::where('id_evento', $request->id_evento)->first();

        if ($evidenciaExistente) {
            if ($evidenciaExistente->estatus !== 'Rechazada') {
                return back()->withErrors('Este evento ya tiene una evidencia ' . strtolower($evidenciaExistente->estatus) . '. No se puede subir otra mientras esa siga así.');
            }

            Storage::disk('public')->delete($evidenciaExistente->archivo);
            $evidenciaExistente->delete();
        }

        // Importante: 'evidencias' SIN slash al final, y el disco 'public' explícito.
        // Así el archivo queda en storage/app/public/evidencias/ y storage:link lo sirve bien.
        $filePath = $request->file('archivo')->store('evidencias', 'public');

        Evidencias::create([
            'id_usuario' => Auth::id(),
            'id_evento' => $request->id_evento,
            'archivo' => $filePath,
            'fecha_registro' => Carbon::now(),
            'estatus' => 'Pendiente',
        ]);

        return redirect()->route('evidencias.index')
            ->with('success', 'Evidencia creada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_evento' => 'required|exists:eventos,id_evento',
            'archivo' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($motivo = $this->motivoSinPermiso($request->id_evento)) {
            return back()->withErrors($motivo);
        }

        $evidencia = Evidencias::findOrFail($id);

        $data = $request->only('id_evento');

        if ($request->hasFile('archivo')) {
            // Mismo disco y carpeta que store(), para que quede consistente y visible.
            Storage::disk('public')->delete($evidencia->archivo);
            $data['archivo'] = $request->file('archivo')->store('evidencias', 'public');
            // Si se sube un archivo nuevo, vuelve a quedar pendiente de revisión.
            $data['estatus'] = 'Pendiente';
        }

        $evidencia->update($data);

        return redirect()->route('evidencias.index')
            ->with('success', 'Evidencia actualizada exitosamente.');
    }

    public function edit($id)
    {
        $evidencia = Evidencias::find($id);
        $eventos = Eventos::where('id_maestro', Auth::id())->get();

        return view('evidencias.edit', compact('evidencia', 'eventos'));
    }

    public function show($id)
    {
        $evidencia = Evidencias::findOrFail($id);

        if (!Storage::disk('public')->exists($evidencia->archivo)) {
            return abort(404);
        }

        return view('evidencias.show', compact('evidencia'));
    }


    public function destroy($id)
    {
        $evidencia = Evidencias::find($id);

        if ($motivo = $this->motivoSinPermiso($evidencia->id_evento)) {
            return back()->withErrors($motivo);
        }

        Storage::disk('public')->delete($evidencia->archivo);
        $evidencia->delete();

        return redirect()->route('evidencias.index')
            ->with('success', 'Evidencia eliminada exitosamente.');
    }

    /**
     * El Director aprueba la evidencia, pero solo si es el Director asignado
     * al evento al que pertenece esa evidencia. Se puede usar aunque ya estuviera
     * Aprobada o Rechazada antes — el Director puede cambiar de opinión.
     */
    public function aprobar($id)
    {
        $evidencia = Evidencias::findOrFail($id);

        if ($motivo = $this->motivoSinPermisoDirector($evidencia)) {
            return back()->withErrors($motivo);
        }

        $evidencia->update(['estatus' => 'Aprobada']);

        return redirect()->route('evidencias.index')->with('success', 'Evidencia aprobada.');
    }

    public function rechazar($id)
    {
        $evidencia = Evidencias::findOrFail($id);

        if ($motivo = $this->motivoSinPermisoDirector($evidencia)) {
            return back()->withErrors($motivo);
        }

        $evidencia->update(['estatus' => 'Rechazada']);

        return redirect()->route('evidencias.index')->with('success', 'Evidencia rechazada. El maestro podrá subir otra.');
    }

    /**
     * El Maestro debe ser justo el asignado a ese evento.
     */
    private function motivoSinPermiso($id_evento): ?string
    {
        $evento = Eventos::find($id_evento);

        if (! $evento || (int) $evento->id_maestro !== (int) Auth::id()) {
            return 'Solo puedes gestionar evidencia de los eventos que tienes asignados.';
        }

        return null;
    }

    /**
     * Para aprobar/rechazar: el Director debe ser justo el asignado a ese evento.
     */
    private function motivoSinPermisoDirector(Evidencias $evidencia): ?string
    {
        $evento = Eventos::find($evidencia->id_evento);

        if (! $evento || (int) $evento->id_director !== (int) Auth::id()) {
            return 'Solo puedes aprobar o rechazar evidencia de los eventos que tienes asignados como Director.';
        }

        return null;
    }
}