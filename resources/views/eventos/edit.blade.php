@extends('template')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Editar Evento</h1>

        <form action="{{ route('eventos.update', $evento->id_evento) }}" method="POST" class="bg-white shadow-md rounded-lg px-8 py-6 mb-6">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre_evento" class="block text-gray-700 font-bold mb-2">Nombre del Evento:</label>
                <input type="text" name="nombre_evento" id="nombre_evento" value="{{ $evento->nombre_evento }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="fecha_inicio" class="block text-gray-700 font-bold mb-2">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ $evento->fecha_inicio }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="fecha_fin" class="block text-gray-700 font-bold mb-2">Fecha de Fin:</label>
                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ $evento->fecha_fin }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700 font-bold mb-2">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $evento->descripcion }}</textarea>
            </div>

            <div class="mb-4">
                <label for="duracion_horas" class="block text-gray-700 font-bold mb-2">Duración (Horas):</label>
                <input type="number" name="duracion_horas" id="duracion_horas" value="{{ $evento->duracion_horas }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="modalidad" class="block text-gray-700 font-bold mb-2">Modalidad:</label>
                <select name="modalidad" id="modalidad" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="Virtual" {{ $evento->modalidad == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                    <option value="Presencial" {{ $evento->modalidad == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="folio" class="block text-gray-700 font-bold mb-2">Folio:</label>
                <input type="text" name="folio" id="folio" value="{{ $evento->folio }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="observaciones" class="block text-gray-700 font-bold mb-2">Observaciones:</label>
                <textarea name="observaciones" id="observaciones" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $evento->observaciones }}</textarea>
            </div>

            <div class="mb-4">
                <label for="academia" class="block text-gray-700 font-bold mb-2">Academia:</label>
                <input type="text" name="academia" id="academia" value="{{ $evento->academia }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar Cambios</button>
                <a href="{{ route('eventos.show', $evento->id_evento) }}" class="ml-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
