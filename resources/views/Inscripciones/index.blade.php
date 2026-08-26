@extends('template')
@section('title', 'Eventos Disponibles')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Eventos Disponibles</h1>

    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($eventos as $evento)
            <div class="bg-white rounded-lg shadow-md p-4">
                <h2 class="text-xl font-bold mb-2">{{ $evento->nombre_evento }}</h2>
                <p>{{ $evento->descripcion }}</p>
                <p class="mt-2"><strong>Fecha:</strong> {{ $evento->fecha_inicio }} - {{ $evento->fecha_fin }}</p>
                <p class="mt-2"><strong>Modalidad:</strong> {{ $evento->modalidad }}</p>
                <a href="{{ route('inscripciones.create', $evento->id_evento) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block">Inscribirse</a>
            </div>
        @empty
            <p class="text-center">No hay eventos disponibles en este momento.</p>
        @endforelse
    </div>
</div>
@endsection
