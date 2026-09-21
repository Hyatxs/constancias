@extends('template')

@section('title', 'Crear Horario de Evento')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Agregar Nuevo Horario</h1>

    {{-- Mostrar errores de validación si los hay --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mostrar detalles del evento si $id_evento está presente --}}
    @if ($id_evento)
        @php
            $evento = \App\Models\Eventos::find($id_evento);
        @endphp
        <div class="bg-gray-100 p-4 mb-4 rounded">
            <h2 class="text-xl font-semibold">Detalles del Evento</h2>
            @if ($evento)
                <p><strong>Nombre del Evento:</strong> {{ $evento->nombre_evento }}</p>
                <p><strong>Fecha de Inicio:</strong> {{ $evento->fecha_inicio }}</p>
                <p><strong>Fecha de Fin:</strong> {{ $evento->fecha_fin }}</p>
            @else
                <p>Evento no encontrado.</p>
            @endif
        </div>
    @endif

    <form method="POST" action="{{ route('horarios.store') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        @if ($id_evento)
            {{-- Ya sabemos el evento (llegamos desde "Asignar Horario" en el evento), no hace falta preguntarlo --}}
            <input type="hidden" name="id_evento" value="{{ $id_evento }}">
        @else
            {{-- Se entró desde el menú genérico "Horarios", sin evento preseleccionado: se pregunta --}}
            <div class="mb-4">
                <label for="id_evento" class="block text-gray-700 text-sm font-bold mb-2">Evento</label>
                <select name="id_evento" id="id_evento" required class="form-control border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Seleccione un evento</option>
                    @foreach ($eventos as $eventoOpcion)
                        <option value="{{ $eventoOpcion->id_evento }}">{{ $eventoOpcion->nombre_evento }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="mb-4">
            <label for="dia" class="block text-gray-700 text-sm font-bold mb-2">Día</label>
            <select name="dia" id="dia" class="form-control border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Seleccione un día</option>
                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                    <option value="{{ $dia }}">{{ $dia }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="ubicacion" class="block text-gray-700 text-sm font-bold mb-2">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
        </div>

        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label for="hora_inicio" class="block text-gray-700 text-sm font-bold mb-2">Hora de Inicio</label>
                <input type="time" name="hora_inicio" id="hora_inicio" class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div>
                <label for="hora_fin" class="block text-gray-700 text-sm font-bold mb-2">Hora de Fin</label>
                <input type="time" name="hora_fin" id="hora_fin" class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
        </div>

        <div class="flex items-center justify-between">
            @if ($id_evento)
                <a href="{{ route('eventos.show', ['evento' => $id_evento]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Volver</a>
            @else
                <a href="{{ route('horarios.index') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Volver</a>
            @endif

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear</button>
        </div>
    </form>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush