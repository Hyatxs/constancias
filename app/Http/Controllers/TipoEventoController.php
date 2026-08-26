<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoEvento;

class TipoEventoController extends Controller
{
    public function index()
    {
        $tipos = TipoEvento::all();
        return view('tipos_eventos.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos_eventos.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        TipoEvento::create($validatedData);
        return redirect()->route('tipos-eventos.index')->with('success', 'Tipo de Evento creado correctamente');
    }

    public function edit($id_tipo_evento)
    {
        $tipoEvento = TipoEvento::findOrFail($id_tipo_evento);
        return view('tipos_eventos.edit', compact('tipoEvento'));
    }

    public function update(Request $request, $id_tipo_evento)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipoEvento = TipoEvento::findOrFail($id_tipo_evento);
        $tipoEvento->update($validatedData);

        return redirect()->route('tipos-eventos.index')->with('success', 'Tipo de Evento actualizado correctamente');
    }

    public function destroy($id_tipo_evento)
    {
        $tipoEvento = TipoEvento::findOrFail($id_tipo_evento);
        $tipoEvento->delete();

        return redirect()->route('tipos-eventos.index')->with('success', 'Tipo de Evento eliminado correctamente');
    }

    public function show($id_tipo_evento)
    {
        $tipoEvento = TipoEvento::findOrFail($id_tipo_evento);
        return view('tipos_eventos.show', compact('tipoEvento'));
    }
}
