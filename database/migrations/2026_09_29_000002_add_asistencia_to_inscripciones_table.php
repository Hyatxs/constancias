<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscripciones_eventos', function (Blueprint $table) {
            // El Maestro la marca después de que el evento ya pasó; empieza en Pendiente.
            $table->enum('asistencia', ['Pendiente', 'Asistió', 'No asistió'])
                ->default('Pendiente')
                ->after('estatus');
        });
    }

    public function down(): void
    {
        Schema::table('inscripciones_eventos', function (Blueprint $table) {
            $table->dropColumn('asistencia');
        });
    }
};