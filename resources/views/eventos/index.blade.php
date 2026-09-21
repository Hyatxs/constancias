@extends('template')
@section('title','Lista de Eventos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Lista de Eventos</h1>
    @if (in_array(auth()->user()->rol, ['Coordinador', 'Director', 'Administrador']))
        <div class="flex justify-end mb-4">
            <a href="{{ route('eventos.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Crear Nuevo Evento</a>
        </div>
    @endif
    <div class="overflow-x-auto">
        <table class="table-auto w-full mt-4" id="eventosTable">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Nombre</th>
                    <th class="py-3 px-6 text-left">Fecha de Inicio</th>
                    <th class="py-3 px-6 text-left">Fecha de Fin</th>
                    <th class="py-3 px-6 text-left">Modalidad</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($eventos as $evento)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <a href="{{ route('eventos.show', $evento->id_evento) }}" class="text-blue-500 hover:text-blue-700 font-semibold">{{ $evento->nombre_evento }}</a>
                    </td>
                    <td class="py-3 px-6 text-left">{{ $evento->fecha_inicio }}</td>
                    <td class="py-3 px-6 text-left">{{ $evento->fecha_fin }}</td>
                    <td class="py-3 px-6 text-left">{{ $evento->modalidad }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="{{ route('eventos.show', $evento->id_evento) }}" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if (in_array(auth()->user()->rol, ['Coordinador', 'Director', 'Administrador']))
                                <a href="{{ route('eventos.edit', $evento->id_evento) }}" class="w-4 mr-2 transform hover:text-yellow-500 hover:scale-110">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('eventos.destroy', $evento->id_evento) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-4 transform hover:text-red-500 hover:scale-110">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
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
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush