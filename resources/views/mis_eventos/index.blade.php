{{-- resources/views/mis_eventos/index.blade.php --}}

@extends('template')
@section('title', 'Mis Eventos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Mis Inscripciones a eventos</h1>

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

    <!-- Inscripciones en proceso -->
    <h2 class="text-xl font-semibold my-4">Eventos en Proceso</h2>
    @if ($inscripciones_en_proceso->isEmpty())
        <p class="text-gray-500">No tienes eventos en proceso.</p>
    @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full mt-4" id="inscripcionesEnProcesoTable">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Nombre del Evento</th>
                        <th class="py-3 px-6 text-center">Fecha de Inscripción</th>
                        <th class="py-3 px-6 text-center">Estatus</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($inscripciones_en_proceso as $inscripcion)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left">{{ $inscripcion->evento->nombre_evento }}</td>
                            <td class="py-3 px-6 text-center">{{ $inscripcion->fecha_inscripcion }}</td>
                            <td class="py-3 px-6 text-center">
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full
                                    @if($inscripcion->estatus == 'Finalizado') bg-green-200 text-green-700
                                    @else bg-yellow-200 text-yellow-700
                                    @endif">
                                    {{ $inscripcion->estatus }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    <a href="{{ route('inscripciones.show', $inscripcion->id_inscripcion) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
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

    <!-- Inscripciones finalizadas -->
    <h2 class="text-xl font-semibold my-4">Eventos Finalizados</h2>
    @if ($inscripciones_finalizadas->isEmpty())
        <p class="text-gray-500">No tienes eventos finalizados.</p>
    @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full mt-4" id="inscripcionesFinalizadasTable">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Nombre del Evento</th>
                        <th class="py-3 px-6 text-center">Fecha de Inscripción</th>
                        <th class="py-3 px-6 text-center">Estatus</th>
                        <th class="py-3 px-6 text-center">Constancia</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($inscripciones_finalizadas as $inscripcion)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left">{{ $inscripcion->evento->nombre_evento }}</td>
                            <td class="py-3 px-6 text-center">{{ $inscripcion->fecha_inscripcion }}</td>
                            <td class="py-3 px-6 text-center">
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full bg-green-200 text-green-700">
                                    {{ $inscripcion->estatus }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    <a href="{{ route('inscripciones.show', $inscripcion->id_inscripcion) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if ($inscripcion->motivo_constancia_bloqueada)
                                        {{-- Todavía no cumple las condiciones: se muestra el motivo en vez de los botones --}}
                                        <span class="text-xs text-gray-500 italic" title="{{ $inscripcion->motivo_constancia_bloqueada }}">
                                            <i class="fas fa-lock mr-1"></i>En espera
                                        </span>
                                    @else
                                        <!-- Botón para ver la constancia -->
                                        <a href="{{ route('mis_eventos.ver_constancia', $inscripcion->evento->id_evento) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110" target="_blank">
                                            <i class="fa-solid fa-book"></i>
                                        </a>
                                        <!-- Botón para descargar la constancia -->
                                        <a href="{{ route('mis_eventos.descargar_constancia', $inscripcion->evento->id_evento) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                                @if ($inscripcion->motivo_constancia_bloqueada)
                                    <p class="text-xs text-gray-400 mt-1">{{ $inscripcion->motivo_constancia_bloqueada }}</p>
                                @endif
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
            const dataTableProceso = new simpleDatatables.DataTable("#inscripcionesEnProcesoTable");
            const dataTableFinalizado = new simpleDatatables.DataTable("#inscripcionesFinalizadasTable");
        });
    </script>
@endpush