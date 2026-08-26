@extends('template')
@section('title','Editar Evidencia')

@push('css')
    <!-- Agrega aquí cualquier CSS adicional si es necesario -->
@endpush

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Editar Evidencia</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('evidencias.update', $evidencia->id_evidencia) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="id_evento" class="block text-gray-700 font-bold mb-2">Evento:</label>
            <select name="id_evento" id="id_evento" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id_evento }}" {{ $evento->id_evento == $evidencia->id_evento ? 'selected' : '' }}>
                        {{ $evento->nombre_evento }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="archivo" class="block text-gray-700 font-bold mb-2">Archivo (PDF):</label>
            <input type="file" name="archivo" id="archivo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @if ($evidencia->archivo)
                <p class="mt-2 text-gray-600">Archivo actual: <a href="{{ asset('storage/' . $evidencia->archivo) }}" class="text-blue-500 hover:text-blue-700" target="_blank">Ver PDF</a></p>
            @endif
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Actualizar Evidencia
            </button>
        </div>
    </form>
</div>
@endsection

@push('js')
    <!-- Agrega aquí cualquier JS adicional si es necesario -->
@endpush
