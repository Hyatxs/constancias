{{-- resources/views/eventos_director/historial.blade.php --}}

@extends('template')
@section('title','Historial de Eventos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Historial de Eventos</h1>

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

    @if ($eventos->isEmpty())
        <p class="text-gray-500">No hay eventos aceptados o rechazados.</p>
    @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full mt-4" id="historialEventosTable">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Nombre</th>
                        <th class="py-3 px-6 text-center">Fecha de Inicio</th>
                        <th class="py-3 px-6 text-center">Estatus</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($eventos as $evento)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left">{{ $evento->nombre_evento }}</td>
                            <td class="py-3 px-6 text-center">{{ $evento->fecha_inicio }}</td>
                            <td class="py-3 px-6 text-center">
                                @if($evento->estatus == 'Aceptado')
                                    <span class="text-green-500">{{ $evento->estatus }}</span>
                                @elseif($evento->estatus == 'Rechazado')
                                    <span class="text-red-500">{{ $evento->estatus }}</span>
                                @else
                                    <span class="text-purple-500">{{ $evento->estatus }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-center">
                                <a href="{{ route('eventos.show', $evento->id_evento) }}" class="text-blue-500 hover:text-blue-700">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dataTable = new simpleDatatables.DataTable('#historialEventosTable');
        });
    </script>
@endpush
