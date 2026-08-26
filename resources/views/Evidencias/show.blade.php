@extends('template')
@section('title', 'Ver Evidencia')

@push('css')
    <!-- Agrega aquí cualquier CSS adicional si es necesario -->
@endpush

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Ver Evidencia</h1>

    @if ($evidencia->archivo)
        <div class="flex justify-center">
            <iframe src="{{ Storage::url($evidencia->archivo) }}" width="100%" height="600px" title="Vista del archivo PDF"></iframe>
        </div>
    @else
        <p class="text-red-500">No hay archivo disponible para mostrar.</p>
    @endif

    <div class="flex justify-end mt-4">
        <a href="{{ route('evidencias.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Volver a la Lista</a>
    </div>
</div>
@endsection

@push('js')
    <!-- Agrega aquí cualquier JS adicional si es necesario -->
@endpush
