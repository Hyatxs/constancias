@extends('template')

@section('title', 'Editar Horario de Evento')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-4">Editar Horario de Evento</h1>

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

    <form method="POST" action="{{ route('horarios.update', $horario->id_evento_horario) }}" class="bg-white shadow-md rounded-lg px-8 py-6 mb-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="id_evento" class="block text-gray-700 font-bold mb-2">Evento:</label>
            <select name="id_evento" id="id_evento" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id_evento }}" {{ old('id_evento', $horario->id_evento) == $evento->id_evento ? 'selected' : '' }}>{{ $evento->nombre_evento }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="dia" class="block text-gray-700 font-bold mb-2">Día:</label>
            <select name="dia" id="dia" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                    <option value="{{ $dia }}" {{ old('dia', $horario->dia) === $dia ? 'selected' : '' }}>{{ $dia }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="ubicacion" class="block text-gray-700 font-bold mb-2">Ubicación:</label>
            <input type="text" name="ubicacion" id="ubicacion" value="{{ old('ubicacion', $horario->ubicacion) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="hora_inicio" class="block text-gray-700 font-bold mb-2">Hora de Inicio:</label>
            <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio', $horario->hora_inicio) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-6">
            <label for="hora_fin" class="block text-gray-700 font-bold mb-2">Hora de Fin:</label>
            <input type="time" name="hora_fin" id="hora_fin" value="{{ old('hora_fin', $horario->hora_fin) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('horarios.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Volver a Horarios</a>
            <button type="submit" class="ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Actualizar</button>
        </div>
    </form>
</div>
@endsection