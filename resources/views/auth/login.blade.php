<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Pantalla de Inicio de Sesión</title>
</head>
<body>

<section class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-[#7ad3f62a] flex rounded-2xl shadow-lg max-w-3xl p-4">
        <div class="w-full px-16">
            <h2 class="font-bold text-2xl text-[#4527a5] text-center">Constancias Buap</h2>
            <p class="text-sm mt-7 text-[#6c57b1] text-opacity-70 text-center">Si ya eres miembro, inicia sesión fácilmente</p>
            <form class="flex flex-col gap-4 mt-8" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input id="email" class="p-2 mt-1 rounded-xl border w-full" type="email" name="email" placeholder="Tu correo electrónico" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input id="password" class="p-2 mt-1 rounded-xl border w-full" type="password" name="password" placeholder="Tu contraseña" required>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="Login-button rounded-xl text-white bg-[#4527a5] py-2 mt-4">Iniciar Sesión</button>
            </form>
            <p class="mt-5 text-xs border-b border-gray-400 py-4">
                <a href="{{ route('password-recovery') }}" class="text-[#4527a5]">¿Olvidaste tu contraseña?</a>
            </p>
            <div class="mt-3 text-xs flex justify-between items-center">
                <p>
                    <a href="{{ route('register') }}" class="text-[#4527a5]">¿No tienes una cuenta?</a>
                </p>
                <a href="{{ route('register') }}" class="py-2 px-5 bg-white border rounded-xl">Registrarse</a>
            </div>
        </div>
    </div>
</section>

</body>
</html>
