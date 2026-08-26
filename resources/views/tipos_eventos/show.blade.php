@extends('template')

@section('content')
    <div class="container">
        <h1>Detalles del Tipo de Evento: {{ $tipoEvento->nombre }}</h1>
        <div>
            <strong>ID:</strong> {{ $tipoEvento->id_tipo_evento }}
        </div>
        <div>
            <strong>Nombre:</strong> {{ $tipoEvento->nombre }}
        </div>
        <div>
            <a href="{{ route('tipos-eventos.edit', ['tipos_evento' => $tipoEvento->id_tipo_evento]) }}" class="btn btn-warning">Editar</a>
        </div>
    </div>
@endsection
