@extends('template')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Editar Evento</h1>

        {{-- Mostrar errores de validación si los hay --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('eventos.update', $evento->id_evento) }}" method="POST" class="bg-white shadow-md rounded-lg px-8 py-6 mb-6">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre_evento" class="block text-gray-700 font-bold mb-2">Nombre del Evento:</label>
                <input type="text" name="nombre_evento" id="nombre_evento" value="{{ old('nombre_evento', $evento->nombre_evento) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="id_maestro" class="block text-gray-700 font-bold mb-2">Profesor asignado:</label>
                <select name="id_maestro" id="id_maestro" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Seleccione un Profesor</option>
                    @foreach ($maestros as $maestro)
                        <option value="{{ $maestro->id }}" {{ old('id_maestro', $evento->id_maestro) == $maestro->id ? 'selected' : '' }}>
                            {{ $maestro->nombre }} {{ $maestro->apellido_paterno }} {{ $maestro->apellido_materno }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="fecha_inicio" class="block text-gray-700 font-bold mb-2">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio', $evento->fecha_inicio) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="fecha_fin" class="block text-gray-700 font-bold mb-2">Fecha de Fin:</label>
                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin', $evento->fecha_fin) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700 font-bold mb-2">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('descripcion', $evento->descripcion) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="duracion_horas" class="block text-gray-700 font-bold mb-2">Duración (Horas):</label>
                <input type="number" name="duracion_horas" id="duracion_horas" value="{{ old('duracion_horas', $evento->duracion_horas) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="modalidad" class="block text-gray-700 font-bold mb-2">Modalidad:</label>
                <select name="modalidad" id="modalidad" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="Virtual" {{ old('modalidad', $evento->modalidad) == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                    <option value="Presencial" {{ old('modalidad', $evento->modalidad) == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="folio" class="block text-gray-700 font-bold mb-2">Folio:</label>
                <input type="text" name="folio" id="folio" value="{{ old('folio', $evento->folio) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="observaciones" class="block text-gray-700 font-bold mb-2">Observaciones:</label>
                <textarea name="observaciones" id="observaciones" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('observaciones', $evento->observaciones) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="academia" class="block text-gray-700 font-bold mb-2">Academia:</label>
                <input type="text" name="academia" id="academia" value="{{ old('academia', $evento->academia) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar Cambios</button>
                <a href="{{ route('eventos.show', $evento->id_evento) }}" class="ml-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</a>
            </div>
        </form>
    </div>
@endsection