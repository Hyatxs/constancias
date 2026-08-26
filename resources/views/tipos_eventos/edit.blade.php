@extends('template')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Editar Tipo de Evento</h1>

        <form action="{{ route('tipos-eventos.update', $tipoEvento->id_tipo_evento) }}" method="POST" class="bg-white shadow-md rounded-lg px-8 py-6 mb-6">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre del Tipo de Evento:</label>
                <input type="text" name="nombre" id="nombre" value="{{ $tipoEvento->nombre }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Actualizar</button>
                <a href="{{ route('tipos-eventos.index') }}" class="ml-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
