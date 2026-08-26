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
        Schema::create('eventos', function (Blueprint $table) {
            $table->integer('id_evento', true);
            $table->foreignId('id_tipo_evento')->constrained('tipos_eventos', 'id_tipo_evento');
            $table->integer('id_creador')->index('fk_eventos_usuarios_creador');
            $table->integer('id_director')->index('fk_eventos_usuarios_director')->comment('El director actual quien va aceptar o rechazar el evento');
            $table->string('nombre_evento', 100);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->text('descripcion')->nullable();
            $table->integer('duracion_horas');
            $table->enum('modalidad', ['Virtual', 'Presencial'])->default('Presencial');
            $table->enum('estatus', ['Aceptado', 'Pendiente', 'Rechazado'])->default('Pendiente');
            $table->string('folio', 5);
            $table->text('observaciones')->nullable();
            $table->string('academia', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('eventos');



    }

};
