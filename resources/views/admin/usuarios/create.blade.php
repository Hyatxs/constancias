@extends('template')
@section('title','Crear Usuario')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-6 text-center">Crear usuario</h1>

    <div class="max-w-xl mx-auto bg-white shadow rounded p-6">

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.usuarios.store') }}">
            @csrf

            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-semibold mb-1">Nombre</label>
                <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required autofocus
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="apellido_paterno" class="block text-gray-700 font-semibold mb-1">Apellido Paterno</label>
                <input id="apellido_paterno" type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="apellido_materno" class="block text-gray-700 font-semibold mb-1">Apellido Materno</label>
                <input id="apellido_materno" type="text" name="apellido_materno" value="{{ old('apellido_materno') }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-1">Correo Electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-1">Contraseña</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="password-confirm" class="block text-gray-700 font-semibold mb-1">Confirmar Contraseña</label>
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="rol" class="block text-gray-700 font-semibold mb-1">Rol</label>
                {{-- Aquí SÍ van los 5 roles, a diferencia del registro público que solo usa roles() --}}
                <select id="rol" name="rol" required class="w-full border rounded px-3 py-2">
                    @foreach(\App\Models\Usuarios::allRoles() as $key => $value)
                        <option value="{{ $key }}" {{ old('rol') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="matricula" class="block text-gray-700 font-semibold mb-1">Matrícula</label>
                <input id="matricula" type="text" name="matricula" value="{{ old('matricula') }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-6">
                <label for="telefono" class="block text-gray-700 font-semibold mb-1">Teléfono</label>
                <input id="telefono" type="text" name="telefono" value="{{ old('telefono') }}"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.usuarios.index') }}" class="text-gray-600 hover:text-gray-800">
                    ← Volver al listado
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Crear usuario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection