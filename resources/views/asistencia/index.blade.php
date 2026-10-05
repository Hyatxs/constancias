@extends('template')
@section('title', 'Asistencia')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Marcar Asistencia</h1>

    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="table-auto w-full mt-4">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Evento</th>
                    <th class="py-3 px-6 text-left">Fecha</th>
                    <th class="py-3 px-6 text-left">Inscritos</th>
                    <th class="py-3 px-6 text-center">Acción</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse ($eventos as $evento)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $evento->nombre_evento }}</td>
                    <td class="py-3 px-6 text-left">{{ $evento->fecha_inicio }} - {{ $evento->fecha_fin }}</td>
                    <td class="py-3 px-6 text-left">{{ $evento->inscripciones_count }}</td>
                    <td class="py-3 px-6 text-center">
                        <a href="{{ route('asistencia.show', $evento->id_evento) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                            Marcar asistencia
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-500">
                        Todavía no tienes eventos finalizados para marcar asistencia.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection