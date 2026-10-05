<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evidencias_eventos', function (Blueprint $table) {
            // Toda evidencia nueva empieza como Pendiente hasta que el Director la revise.
            $table->enum('estatus', ['Pendiente', 'Aprobada', 'Rechazada'])
                ->default('Pendiente')
                ->after('archivo');
        });
    }

    public function down(): void
    {
        Schema::table('evidencias_eventos', function (Blueprint $table) {
            $table->dropColumn('estatus');
        });
    }
};