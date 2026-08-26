<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inscripciones_eventos', function (Blueprint $table) {
            $table->integer('id_inscripcion', true);
            $table->integer('id_usuario')->index('fk_incripciones_usuarios')->comment('Quien tomara el evento, curso, etc.');
            $table->integer('id_evento')->index('fk_incripciones_eventos');
            $table->enum('estatus', ['En proceso', 'Finalizado', 'Rechazado'])->default('En proceso');
            $table->dateTime('fecha_inscripcion')->useCurrent();
            $table->date('fecha_envio')->nullable();

    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripciones_eventos');
    }
};
