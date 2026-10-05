@extends('template')
@section('title', 'Asistencia - ' . $evento->nombre_evento)

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Asistencia: {{ $evento->nombre_evento }}</h1>

    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="table-auto w-full mt-4">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Alumno</th>
                    <th class="py-3 px-6 text-left">Estatus actual</th>
                    <th class="py-3 px-6 text-center">Marcar</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse ($inscripciones as $inscripcion)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">
                        {{ $inscripcion->usuario->nombre ?? 'Alumno no encontrado' }}
                        {{ $inscripcion->usuario->apellido_paterno ?? '' }}
                        {{ $inscripcion->usuario->apellido_materno ?? '' }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($inscripcion->asistencia === 'Asistió') bg-green-200 text-green-800
                            @elseif($inscripcion->asistencia === 'No asistió') bg-red-200 text-red-800
                            @else bg-yellow-200 text-yellow-800
                            @endif">
                            {{ $inscripcion->asistencia }}
                        </span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <form action="{{ route('asistencia.marcar', $inscripcion->id_inscripcion) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="asistencia" value="Asistió">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white text-xs font-bold py-1 px-2 rounded">Asistió</button>
                        </form>
                        <form action="{{ route('asistencia.marcar', $inscripcion->id_inscripcion) }}" method="POST" class="inline ml-1">
                            @csrf
                            <input type="hidden" name="asistencia" value="No asistió">
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-xs font-bold py-1 px-2 rounded">No asistió</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-6 text-center text-gray-500">
                        Nadie se inscribió a este evento.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('asistencia.index') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Volver</a>
    </div>
</div>
@endsection