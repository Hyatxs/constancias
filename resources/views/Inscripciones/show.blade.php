{{-- resources/views/inscripciones/show.blade.php --}}

@extends('template')
@section('title', 'Detalles del Evento')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Detalles del Evento</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Información del Evento</h2>

        <p><strong>Nombre del Evento:</strong> {{ $inscripcion->evento->nombre_evento }}</p>
        <p><strong>Fecha de Inicio:</strong> {{ $inscripcion->evento->fecha_inicio }}</p>
        <p><strong>Fecha de Fin:</strong> {{ $inscripcion->evento->fecha_fin }}</p>
        <p><strong>Descripción:</strong> {{ $inscripcion->evento->descripcion }}</p>
        <p><strong>Modalidad:</strong> {{ $inscripcion->evento->modalidad }}</p>
        <p><strong>Duración (Horas):</strong> {{ $inscripcion->evento->duracion_horas }}</p>
        <p><strong>Folio:</strong> {{ $inscripcion->evento->folio }}</p>
        <p><strong>Academia:</strong> {{ $inscripcion->evento->academia }}</p>

        <!-- Agrega aquí cualquier otra información relevante sobre el evento -->

        <a href="{{ route('mis-eventos.index') }}" class="text-blue-500 hover:underline">Regresar a Mis Eventos</a>
    </div>
</div>
@endsection
