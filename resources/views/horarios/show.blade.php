@extends('template')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Detalles del Horario</h1>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="mb-4">
                <strong class="text-gray-700">Evento:</strong>
                <p class="mt-2">{{ $evento->nombre_evento }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Día:</strong>
                <p class="mt-2">{{ $horario->dia }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Hora de Inicio:</strong>
                <p class="mt-2">{{ $horario->hora_inicio }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Hora de Fin:</strong>
                <p class="mt-2">{{ $horario->hora_fin }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Ubicación:</strong>
                <p class="mt-2">{{ $horario->ubicacion }}</p>
            </div>
            <div class="mb-4">
                <strong class="text-gray-700">Observaciones:</strong>
                <p class="mt-2">{{ $horario->observaciones }}</p>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('horarios.edit', $horario->id_evento) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Editar</a>
            <form action="{{ route('horarios.destroy', $horario->id_evento) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Eliminar</button>
            </form>
        </div>

        <div class="mt-4">
            <a href="{{ route('eventos.show', $evento->id_evento) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Volver al Evento</a>
        </div>
    </div>
@endsection
