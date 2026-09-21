@extends('template')
@section('title','Crear Evento')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest/dist/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Crear Nuevo Evento</h1>

        {{-- Mostrar errores de validación si los hay --}}
        @if ($errors->any())
        <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('eventos.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label for="id_director" class="block text-gray-700 text-sm font-bold mb-2">Selecciona un Director para que apruebe el evento:</label>
                <select name="id_director" id="id_director" required class="form-control border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Seleccione un Director</option>
                    @foreach ($directores as $director)
                        <option value="{{ $director->id }}" {{ old('id_director') == $director->id ? 'selected' : '' }}>
                            {{ $director->nombre }} {{ $director->apellido_paterno }} {{ $director->apellido_materno }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="id_maestro" class="block text-gray-700 text-sm font-bold mb-2">Profesor asignado (opcional):</label>
                <select name="id_maestro" id="id_maestro" class="form-control border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Sin asignar</option>
                    @foreach ($maestros as $maestro)
                        <option value="{{ $maestro->id }}" {{ old('id_maestro') == $maestro->id ? 'selected' : '' }}>
                            {{ $maestro->nombre }} {{ $maestro->apellido_paterno }} {{ $maestro->apellido_materno }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="id_evento" class="block text-gray-700 text-sm font-bold mb-2">Tipo de Evento:</label>
                <select name="id_tipo_evento" id="id_evento" required class="form-control border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Seleccione un Tipo de Evento</option>
                    @foreach ($tipos_eventos as $tipo)
                        <option value="{{ $tipo->id_tipo_evento }}" {{ old('id_evento') == $tipo->id_tipo_evento ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="nombre_evento" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Evento:</label>
                <input type="text" name="nombre_evento" id="nombre_evento" value="{{ old('nombre_evento') }}" required class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label for="fecha_inicio" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Inicio:</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}" required class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label for="fecha_fin" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Fin:</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}" required class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>
            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('descripcion') }}</textarea>
            </div>
            <div class="mb-4">
                <label for="duracion_horas" class="block text-gray-700 text-sm font-bold mb-2">Duración (Horas):</label>
                <input type="number" name="duracion_horas" id="duracion_horas" value="{{ old('duracion_horas') }}" required class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Modalidad:</label>
                <div class="flex items-center space-x-6">
                    <label for="virtual" class="flex flex-col items-center cursor-pointer">
                        <div class="relative">
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-md flex items-center justify-center w-20 h-20">
                                <i class="fas fa-laptop-code text-gray-700 text-3xl"></i>
                            </div>
                            <span class="text-gray-700 block text-center mt-2">Virtual</span>
                        </div>
                        <input type="radio" name="modalidad" id="virtual" value="Virtual"  {{ old('modalidad') == 'Virtual' ? 'checked' : '' }}>
                    </label>
                    <label for="presencial" class="flex flex-col items-center cursor-pointer">
                        <div class="relative">
                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-md flex items-center justify-center w-20 h-20">
                                <i class="fas fa-users text-gray-700 text-3xl"></i>
                            </div>
                            <span class="text-gray-700 block text-center mt-2">Presencial</span>
                        </div>
                        <input type="radio" name="modalidad" id="presencial" value="Presencial" {{ old('modalidad') == 'Presencial' ? 'checked' : '' }}>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label for="folio" class="block text-gray-700 text-sm font-bold mb-2">Folio:</label>
                <input type="text" name="folio" id="folio" value="{{ old('folio') }}" required class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="observaciones" class="block text-gray-700 text-sm font-bold mb-2">Observaciones:</label>
                <textarea name="observaciones" id="observaciones" class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('observaciones') }}</textarea>
            </div>
            <div class="mb-4">
                <label for="academia" class="block text-gray-700 text-sm font-bold mb-2">Academia:</label>
                <input type="text" name="academia" id="academia" value="{{ old('academia') }}" required class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Crear</button>
        </form>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables-classic@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const icons = {
                virtual: document.getElementById('icon-virtual'),
                presencial: document.getElementById('icon-presencial'),
            };

            const inputs = {
                virtual: document.getElementById('virtual'),
                presencial: document.getElementById('presencial'),
            };

            icons.virtual.addEventListener('click', function () {
                inputs.virtual.checked = true;
                icons.virtual.classList.add('text-blue-500');
                icons.presencial.classList.remove('text-blue-500');
            });

            icons.presencial.addEventListener('click', function () {
                inputs.presencial.checked = true;
                icons.presencial.classList.add('text-blue-500');
                icons.virtual.classList.remove('text-blue-500');
            });

            // Initial state check to keep the selected icon highlighted on page load
            if (inputs.virtual.checked) {
                icons.virtual.classList.add('text-blue-500');
            }
            if (inputs.presencial.checked) {
                icons.presencial.classList.add('text-blue-500');
            }
        });
    </script>
@endpush
