@extends('template')
@section('title', 'Editar Inscripción')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Editar Inscripción</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inscripciones.update', $inscripcion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="id_evento" class="block text-gray-700">Evento</label>
            <select name="id_evento" id="id_evento" class="w-full border border-gray-300 p-2 rounded">
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id_evento }}" {{ $inscripcion->id_evento == $evento->id_evento ? 'selected' : '' }}>{{ $evento->nombre_evento }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="id_usuario" class="block text-gray-700">Usuario</label>
            <input type="text" name="id_usuario" id="id_usuario" value="{{ $inscripcion->id_usuario }}" readonly class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div class="mb-4">
            <label for="estatus" class="block text-gray-700">Estatus</label>
            <select name="estatus" id="estatus" class="w-full border border-gray-300 p-2 rounded">
                <option value="En proceso" {{ $inscripcion->estatus == 'En proceso' ? 'selected' : '' }}>En proceso</option>
                <option value="Finalizado" {{ $inscripcion->estatus == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                <option value="Rechazado" {{ $inscripcion->estatus == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="fecha_inscripcion" class="block text-gray-700">Fecha de Inscripción</label>
            <input type="date" name="fecha_inscripcion" id="fecha_inscripcion" value="{{ $inscripcion->fecha_inscripcion }}" class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
        </div>
    </form>
</div>
@endsection
