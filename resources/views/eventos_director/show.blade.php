{{-- resources/views/eventos_director/show.blade.php --}}

@extends('template')
@section('title','Detalles del Evento')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">{{ $evento->nombre_evento }}</h1>

    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6 p-6">
        <p><strong>Descripción:</strong> {{ $evento->descripcion }}</p>
        <p><strong>Fecha de Inicio:</strong> {{ $evento->fecha_inicio }}</p>
        <p><strong>Fecha de Fin:</strong> {{ $evento->fecha_fin }}</p>
        <p><strong>Duración (Horas):</strong> {{ $evento->duracion_horas }}</p>
        <p><strong>Modalidad:</strong> {{ $evento->modalidad }}</p>
        <p><strong>Folio:</strong> {{ $evento->folio }}</p>
        <p><strong>Observaciones:</strong> {{ $evento->observaciones }}</p>
        <p><strong>Academia:</strong> {{ $evento->academia }}</p>
        <p><strong>Estado:</strong> {{ $evento->estatus }}</p>

        <h2 class="text-xl font-bold mt-6">Horarios</h2>
        <ul class="list-disc list-inside">
            @foreach ($horarios as $horario)
                <li>{{ $horario->fecha }}: {{ $horario->hora_inicio }} - {{ $horario->hora_fin }}</li>
            @endforeach
        </ul>

        @if(auth()->user()->rol == 'Director' && $evento->estatus == 'Pendiente')
            <div class="flex justify-end mt-6">
                <form action="{{ route('eventos-director.aceptar', $evento->id_evento) }}" method="POST" class="mr-2">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Aceptar</button>
                </form>
                <form action="{{ route('eventos-director.rechazar', $evento->id_evento) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Rechazar</button>
                </form>
            </div>
        @endif
    </div>

    <div class="flex justify-end mt-6">
        <a href="{{ route('eventos-director.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Regresar</a>
    </div>
</div>
@endsection
