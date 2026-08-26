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
    // Otros métodos permanecen iguales...

    public function index()
    {
        $evidencias = Evidencias::all();
        return view('evidencias.index', compact('evidencias'));
    }

    public function create()
    {
        $eventos = Eventos::all();
        return view('evidencias.create', compact('eventos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_evento' => 'required|exists:eventos,id_evento', // Asegúrate de que 'eventos' y 'id_evento' coincidan con tu tabla y columna de eventos
            'archivo' => 'required|file|mimes:pdf|max:2048', // Asegúrate de que sea un archivo PDF
        ]);

        $filePath = $request->file('archivo')->store('public/'); // Guarda el archivo en el directorio 'evidencias'

        Evidencias::create([
            'id_usuario' => Auth::id(), // Usa el ID del usuario autenticado
            'id_evento' => $request->id_evento,
            'archivo' => $filePath,
            'fecha_registro' => Carbon::now(), // Establece la fecha actual
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

        $evidencia = Evidencias::findOrFail($id);

        $data = $request->only('id_evento');

        if ($request->hasFile('archivo')) {
            $filePath = $request->file('archivo')->store('evidencias');
            $data['archivo'] = $filePath;
        }

        $evidencia->update($data);

        return redirect()->route('evidencias.index')
            ->with('success', 'Evidencia actualizada exitosamente.');
    }

    public function edit($id)
    {
        $evidencia = Evidencias::find($id);
        $eventos = Eventos::all();
        return view('evidencias.edit', compact('evidencia', 'eventos'));
    }

    public function show($id)
    {
        $evidencia = Evidencias::findOrFail($id);
        $filePath = 'public/' . $evidencia->archivo;

        if (!Storage::exists($filePath)) {
            return abort(404);
        }

        return view('evidencias.show', compact('evidencia'));
    }


    public function destroy($id)
    {
        $evidencia = Evidencias::find($id);
        Storage::delete($evidencia->archivo); // Elimina el archivo
        $evidencia->delete();

        return redirect()->route('evidencias.index')
            ->with('success', 'Evidencia eliminada exitosamente.');
    }
}
