<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modulo;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            // Estudiante
            ['nombre' => 'Inicio',        'enlace' => 'home',            'icono' => 'fas fa-home',        'rol' => 'Estudiante',    'estatus' => 'Activo', 'orden' => 1],
            ['nombre' => 'Eventos',       'enlace' => 'eventos',         'icono' => 'fas fa-calendar',    'rol' => 'Estudiante',    'estatus' => 'Activo', 'orden' => 2],
            ['nombre' => 'Mis Eventos',   'enlace' => 'mis-eventos',     'icono' => 'fas fa-list',        'rol' => 'Estudiante',    'estatus' => 'Activo', 'orden' => 3],
            ['nombre' => 'Inscripciones', 'enlace' => 'inscripciones',   'icono' => 'fas fa-file-alt',    'rol' => 'Estudiante',    'estatus' => 'Activo', 'orden' => 4],

            // Director
            ['nombre' => 'Inicio',             'enlace' => 'home',               'icono' => 'fas fa-home',    'rol' => 'Director', 'estatus' => 'Activo', 'orden' => 1],
            ['nombre' => 'Eventos por Aprobar','enlace' => 'eventos-director',   'icono' => 'fas fa-check',   'rol' => 'Director', 'estatus' => 'Activo', 'orden' => 2],
            ['nombre' => 'Historial',          'enlace' => 'historial-director', 'icono' => 'fas fa-history', 'rol' => 'Director', 'estatus' => 'Activo', 'orden' => 3],

            // Administrador
            ['nombre' => 'Inicio',         'enlace' => 'home',          'icono' => 'fas fa-home',      'rol' => 'Administrador', 'estatus' => 'Activo', 'orden' => 1],
            ['nombre' => 'Eventos',        'enlace' => 'eventos',       'icono' => 'fas fa-calendar',  'rol' => 'Administrador', 'estatus' => 'Activo', 'orden' => 2],
            ['nombre' => 'Tipos de Evento','enlace' => 'tipos-eventos', 'icono' => 'fas fa-tags',      'rol' => 'Administrador', 'estatus' => 'Activo', 'orden' => 3],
            ['nombre' => 'Horarios',       'enlace' => 'horarios',      'icono' => 'fas fa-clock',     'rol' => 'Administrador', 'estatus' => 'Activo', 'orden' => 4],
            ['nombre' => 'Evidencias',     'enlace' => 'evidencias',    'icono' => 'fas fa-camera',    'rol' => 'Administrador', 'estatus' => 'Activo', 'orden' => 5],
            ['nombre' => 'Crear Usuario',  'enlace' => 'admin.usuarios','icono' => 'fas fa-user-plus', 'rol' => 'Administrador', 'estatus' => 'Activo', 'orden' => 6],

            // Coordinador
            ['nombre' => 'Inicio',   'enlace' => 'home',     'icono' => 'fas fa-home',     'rol' => 'Coordinador', 'estatus' => 'Activo', 'orden' => 1],
            ['nombre' => 'Eventos',  'enlace' => 'eventos',  'icono' => 'fas fa-calendar', 'rol' => 'Coordinador', 'estatus' => 'Activo', 'orden' => 2],
            ['nombre' => 'Horarios', 'enlace' => 'horarios', 'icono' => 'fas fa-clock',    'rol' => 'Coordinador', 'estatus' => 'Activo', 'orden' => 3],

            // Maestro
            ['nombre' => 'Inicio',     'enlace' => 'home',       'icono' => 'fas fa-home',     'rol' => 'Maestro', 'estatus' => 'Activo', 'orden' => 1],
            ['nombre' => 'Eventos',    'enlace' => 'eventos',    'icono' => 'fas fa-calendar', 'rol' => 'Maestro', 'estatus' => 'Activo', 'orden' => 2],
            ['nombre' => 'Evidencias', 'enlace' => 'evidencias', 'icono' => 'fas fa-camera',   'rol' => 'Maestro', 'estatus' => 'Activo', 'orden' => 3],
        ];

        foreach ($modulos as $modulo) {
            Modulo::create($modulo);
        }
    }
}