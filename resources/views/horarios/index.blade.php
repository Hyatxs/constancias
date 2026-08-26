@extends('template')
@section('title','Horarios de Eventos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Horarios de Eventos</h1>
    <div class="flex justify-end mb-4">
        <a href="{{ route('horarios.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Crear Nuevo Horario</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table-auto w-full mt-4" id="horariosTable">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Evento</th>
                    <th class="py-3 px-6 text-left">Día</th>
                    <th class="py-3 px-6 text-left">Ubicación</th>
                    <th class="py-3 px-6 text-left">Hora de Inicio</th>
                    <th class="py-3 px-6 text-left">Hora de Fin</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($horarios as $horario)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">
                        <a href="{{ route('eventos.show', $horario->id_evento) }}" class="text-blue-500 hover:text-blue-700 font-semibold">
                            {{ $horario->eventos->nombre_evento ?? 'Evento no encontrado' }}
                        </a>
                    </td>
                    <td class="py-3 px-6 text-left">{{ $horario->dia }}</td>
                    <td class="py-3 px-6 text-left">{{ $horario->ubicacion }}</td>
                    <td class="py-3 px-6 text-left">{{ $horario->hora_inicio }}</td>
                    <td class="py-3 px-6 text-left">{{ $horario->hora_fin }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <a href="{{ route('horarios.show', $horario) }}" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('horarios.edit', $horario) }}" class="w-4 mr-2 transform hover:text-yellow-500 hover:scale-110">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('horarios.destroy', $horario) }}" method="POST" class="inline">
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
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
