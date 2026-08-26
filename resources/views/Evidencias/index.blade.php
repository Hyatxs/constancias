@extends('template')
@section('title', 'Lista de Evidencias')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Lista de Evidencias</h1>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="flex justify-end mb-4">
        <a href="{{ route('evidencias.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Subir Nueva Evidencia</a>
    </div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full mt-4" id="evidenciasTable">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Evento</th>
                    <th class="py-3 px-6 text-left">Archivo</th>
                    <th class="py-3 px-6 text-left">Fecha de Registro</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($evidencias as $evidencia)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $evidencia->evento->nombre_evento }}</td>
                    <td class="py-3 px-6 text-left">
                        <a href="{{ Storage::url($evidencia->archivo) }}" target="_blank" class="text-blue-500 hover:text-blue-700">Ver PDF</a>
                    </td>
                    <td class="py-3 px-6 text-left">{{ $evidencia->fecha_registro }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="{{ route('evidencias.show', $evidencia->id_evidencia) }}" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('evidencias.edit', $evidencia->id_evidencia) }}" class="w-4 mr-2 transform hover:text-yellow-500 hover:scale-110">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('evidencias.destroy', $evidencia->id_evidencia) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-4 transform hover:text-red-500 hover:scale-110">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest" type="text/javascript"></script>
@endpush
