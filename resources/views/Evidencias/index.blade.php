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

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (auth()->user()->rol === 'Maestro')
        <div class="flex justify-end mb-4">
            <a href="{{ route('evidencias.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Subir Nueva Evidencia</a>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="table-auto w-full mt-4" id="evidenciasTable">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Evento</th>
                    <th class="py-3 px-6 text-left">Archivo</th>
                    <th class="py-3 px-6 text-left">Fecha de Registro</th>
                    <th class="py-3 px-6 text-left">Estatus</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($evidencias as $evidencia)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $evidencia->evento->nombre_evento }}</td>
                    <td class="py-3 px-6 text-left">
                        <a href="{{ Storage::disk('public')->url($evidencia->archivo) }}" target="_blank" class="text-blue-500 hover:text-blue-700">Ver PDF</a>
                    </td>
                    <td class="py-3 px-6 text-left">{{ $evidencia->fecha_registro }}</td>
                    <td class="py-3 px-6 text-left">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($evidencia->estatus === 'Aprobada') bg-green-200 text-green-800
                            @elseif($evidencia->estatus === 'Rechazada') bg-red-200 text-red-800
                            @else bg-yellow-200 text-yellow-800
                            @endif">
                            {{ $evidencia->estatus }}
                        </span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="{{ route('evidencias.show', $evidencia->id_evidencia) }}" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if (auth()->user()->rol === 'Maestro')
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
                            @endif

                            @if (auth()->user()->rol === 'Director')
                                {{-- Sin el @if de estatus: el Director puede cambiar de opinión en cualquier momento --}}
                                <form action="{{ route('evidencias.aprobar', $evidencia->id_evidencia) }}" method="POST" class="inline ml-2">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white text-xs font-bold py-1 px-2 rounded {{ $evidencia->estatus === 'Aprobada' ? 'opacity-50' : '' }}">
                                        Aprobar
                                    </button>
                                </form>
                                <form action="{{ route('evidencias.rechazar', $evidencia->id_evidencia) }}" method="POST" class="inline ml-1">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-xs font-bold py-1 px-2 rounded {{ $evidencia->estatus === 'Rechazada' ? 'opacity-50' : '' }}">
                                        Rechazar
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
@endpush