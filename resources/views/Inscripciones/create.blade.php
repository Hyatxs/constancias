@extends('template')
@section('title', 'Inscribirse al Evento')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Inscribirse al Evento: {{ $evento->nombre_evento }}</h1>

    <form action="{{ route('inscripciones.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_evento" value="{{ $evento->id_evento }}">
        <input type="hidden" name="id_usuario" value="{{ Auth::id() }}">
        <input type="hidden" name="estatus" value="En proceso">
        <input type="hidden" name="fecha_inscripcion" value="{{ now() }}">

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre_evento">
                Nombre del Evento
            </label>
            <input disabled value="{{ $evento->nombre_evento }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre_evento" type="text">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
                Descripción
            </label>
            <textarea disabled class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="descripcion">{{ $evento->descripcion }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="horario">
                Seleccione Horario
            </label>
            <select name="id_evento_horario" id="horario" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @foreach($evento->eventoHorario as $horario)
                    <option value="{{ $horario->id_evento_horario }}">
                        Día: {{ $horario->dia }}, Hora: {{ $horario->hora_inicio }} - {{ $horario->hora_fin }}, Ubicación: {{ $horario->ubicacion }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Inscribirse
            </button>
        </div>
    </form>
</div>
@endsection
