{{-- resources/views/eventos_director/index.blade.php --}}

@extends('template')
@section('title','Eventos Pendientes')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Eventos Pendientes</h1>

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
        <p class="text-gray-500">No hay eventos pendientes.</p>
    @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full mt-4" id="eventosPendientesTable">
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
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full
                                    @if($evento->estatus == 'Aceptado') bg-green-200 text-green-700
                                    @elseif($evento->estatus == 'Rechazado') bg-red-200 text-red-700
                                    @else bg-purple-200 text-purple-700
                                    @endif">
                                    {{ $evento->estatus }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    <a href="{{ route('eventos-director.show', $evento->id_evento) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
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
        window.addEventListener('DOMContentLoaded', () => {
            const dataTable = new simpleDatatables.DataTable("#eventosPendientesTable");
        });
    </script>
@endpush
