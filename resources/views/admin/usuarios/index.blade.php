@extends('template')
@section('title','Usuarios')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Usuarios registrados</h1>

    @if (session('status'))
        <div class="max-w-xl mx-auto mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.usuarios.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Crear Nuevo Usuario
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full mt-4">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Nombre completo</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-left">Rol</th>
                    <th class="py-3 px-6 text-left">Matrícula</th>
                    <th class="py-3 px-6 text-left">Teléfono</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                {{-- Si no hay usuarios todavía (por ejemplo, base de datos recién clonada), --}}
                {{-- se muestra un mensaje en vez de una tabla vacía y rara. --}}
                @forelse($usuarios as $usuario)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        {{ trim("{$usuario->nombre} {$usuario->apellido_paterno} {$usuario->apellido_materno}") }}
                    </td>
                    <td class="py-3 px-6 text-left">{{ $usuario->email }}</td>
                    <td class="py-3 px-6 text-left">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($usuario->rol === 'Administrador') bg-red-200 text-red-800
                            @elseif($usuario->rol === 'Director') bg-purple-200 text-purple-800
                            @elseif($usuario->rol === 'Coordinador') bg-blue-200 text-blue-800
                            @elseif($usuario->rol === 'Maestro') bg-yellow-200 text-yellow-800
                            @else bg-gray-200 text-gray-800
                            @endif">
                            {{ $usuario->rol }}
                        </span>
                    </td>
                    <td class="py-3 px-6 text-left">{{ $usuario->matricula ?? '—' }}</td>
                    <td class="py-3 px-6 text-left">{{ $usuario->telefono ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-gray-500">
                        Todavía no hay usuarios registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection