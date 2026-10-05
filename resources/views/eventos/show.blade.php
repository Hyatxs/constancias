@extends('template')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Detalles del Evento</h1>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="mb-4">
                <strong class="text-gray-700">Nombre del Evento:</strong>
                <p class="mt-2">{{ $evento->nombre_evento }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Profesor asignado:</strong>
                @php
                    $maestroAsignado = $evento->id_maestro ? \App\Models\Usuarios::find($evento->id_maestro) : null;
                @endphp
                <p class="mt-2">
                    @if ($maestroAsignado)
                        {{ $maestroAsignado->nombre }} {{ $maestroAsignado->apellido_paterno }} {{ $maestroAsignado->apellido_materno }}
                    @else
                        <span class="text-red-600">Sin asignar</span>
                    @endif
                </p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Fecha de Inicio:</strong>
                <p class="mt-2">{{ $evento->fecha_inicio }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Fecha de Fin:</strong>
                <p class="mt-2">{{ $evento->fecha_fin }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Descripción:</strong>
                <p class="mt-2">{{ $evento->descripcion }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Duración (Horas):</strong>
                <p class="mt-2">{{ $evento->duracion_horas }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Modalidad:</strong>
                <p class="mt-2">{{ $evento->modalidad }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Folio:</strong>
                <p class="mt-2">{{ $evento->folio }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Observaciones:</strong>
                <p class="mt-2">{{ $evento->observaciones }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Academia:</strong>
                <p class="mt-2">{{ $evento->academia }}</p>
            </div>
        </div>

        @php
            $puedeGestionar = in_array(auth()->user()->rol, ['Coordinador', 'Director', 'Administrador']);
        @endphp

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Horarios Asignados</h2>
            @if ($horarios->isEmpty())
                <p class="text-gray-600">No hay horarios asignados a este evento.</p>
            @else
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200">Día</th>
                            <th class="py-2 px-4 border-b border-gray-200">Hora de Inicio</th>
                            <th class="py-2 px-4 border-b border-gray-200">Hora de Fin</th>
                            <th class="py-2 px-4 border-b border-gray-200">Ubicación</th>
                            @if ($puedeGestionar)
                                <th class="py-2 px-4 border-b border-gray-200">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($horarios as $horario)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $horario->dia }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $horario->hora_inicio }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $horario->hora_fin }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $horario->ubicacion }}</td>
                                @if ($puedeGestionar)
                                    <td class="py-2 px-4 border-b border-gray-200">
                                        <a href="{{ route('horarios.edit', $horario->id_evento_horario) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">Editar</a>
                                        <form action="{{ route('horarios.destroy', $horario->id_evento_horario) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">Eliminar</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if ($puedeGestionar)
            <div class="flex justify-end space-x-4">
                <a href="{{ route('eventos.edit', $evento->id_evento) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Editar</a>
                <form action="{{ route('eventos.destroy', $evento->id_evento) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Eliminar</button>
                </form>
            </div>

            <div class="flex justify-end space-x-4 mt-4">
                <a href="{{ route('horarios.create', ['id_evento' => $evento->id_evento]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Asignar Horario</a>
            </div>
        @elseif (auth()->user()->rol === 'Estudiante' && $evento->estatus === 'Aceptado')
            <div class="flex justify-end mt-4">
                @if ($inscripcion)
                    <span class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded">Ya estás inscrito ({{ $inscripcion->estatus }})</span>
                @else
                    <a href="{{ route('inscripciones.create', $evento->id_evento) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Inscribirme</a>
                @endif
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('eventos.index') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Volver a la lista de eventos</a>
        </div>
    </div>
@endsection