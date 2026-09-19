<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\TipoEventoController;
use App\Http\Controllers\EventoHorarioController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\EvidenciasController;
use App\Http\Controllers\MisEventosController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Admin\UsuariosAdminController;


Route::get('/', function () {
    return redirect('/home');
});
Route::view('/panel', 'panel.index')->name('panel');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Rutas explícitas para manejo de autenticación
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('password-recovery', [LoginController::class, 'showPasswordRecoveryForm'])->name('password-recovery');
Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.submit');


Route::resource('home', HomeController::class);

Route::resource('eventos', EventosController::class);
Route::get('eventos/{evento}', [EventosController::class, 'show'])->name('eventos.show');
Route::middleware('auth')->group(function () {
    Route::get('eventos-director', [EventosController::class, 'indexDirector'])->name('eventos-director.index');
    Route::get('eventos-director/{id}', [EventosController::class, 'showDirector'])->name('eventos-director.show');
    Route::post('eventos-director/{id}/aceptar', [EventosController::class, 'aceptar'])->name('eventos-director.aceptar');
    Route::post('eventos-director/{id}/rechazar', [EventosController::class, 'rechazar'])->name('eventos-director.rechazar');
    // Otras rutas que necesites para el director
});

// routes/web.php

Route::middleware(['auth'])->group(function () {
    Route::get('/historial-director', [EventosController::class, 'historialDirector'])->name('historial-director.index');
});



Route::resource('tipos-eventos', TipoEventoController::class);

Route::resource('horarios', EventoHorarioController::class);
Route::get('eventos/{id_evento}/asignar-horario', [EventoHorarioController::class, 'createFromEvent'])->name('eventos.assignSchedule');
Route::get('horarios/{horario}/edit', [EventoHorarioController::class, 'edit'])->name('horarios.edit');
// Route::put('horarios/{horario}', [EventoHorarioController::class, 'update'])->name('horarios.update');



Route::resource('inscripciones', InscripcionController::class);
Route::get('/inscripciones', [InscripcionController::class, 'index'])->name('inscripciones.index');
// Route::get('/inscripciones/create/{id_evento}', [InscripcionController::class, 'create'])->name('inscripciones.create');
Route::post('/inscripciones', [InscripcionController::class, 'store'])->name('inscripciones.store');
// Route::get('/inscripciones/{id}', [InscripcionController::class, 'show'])->name('inscripciones.show');
Route::resource('evidencias', EvidenciasController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/mis-eventos', [MisEventosController::class, 'index'])->name('mis-eventos.index');
});


// Route::get('/mis-eventos/descargar-constancia/{id_evento}', [MisEventosController::class, 'descargarConstancia'])->name('mis_eventos.descargar_constancia');

Route::get('/mis-eventos/constancia/{eventoId}', [MisEventosController::class, 'verConstancia'])->name('mis_eventos.constancia');

Route::get('/mis-eventos/constancia/{eventoId}', [MisEventosController::class, 'generarConstancia'])->name('mis_eventos.constancia');
Route::get('/constancia/ver/{eventoId}', [MisEventosController::class, 'verConstancia'])->name('mis_eventos.ver_constancia');
Route::get('/constancia/descargar/{eventoId}', [MisEventosController::class, 'descargarConstancia'])->name('mis_eventos.descargar_constancia');


Route::get('/html-constancia', function () {

    $nombre_completo = 'Juan Pérez';

    $nombre_evento = 'Taller de programación';

    $data = [
        'nombre_completo' => $nombre_completo,
        'nombre_evento' => $nombre_evento,
    ];

    return view('templates.constancia.constancia', $data);
});

Route::get('password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('password/confirm', [ConfirmPasswordController::class, 'confirm']);

Route::middleware(['auth', 'password.confirm', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('usuarios/create', [UsuariosAdminController::class, 'create'])->name('usuarios.index');
        Route::post('usuarios', [UsuariosAdminController::class, 'store'])->name('usuarios.store');
    });
