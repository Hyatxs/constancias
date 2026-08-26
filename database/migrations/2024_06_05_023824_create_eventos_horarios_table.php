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
        Schema::create('eventos_horarios', function (Blueprint $table) {
            $table->integer('id_evento_horario', true);
            $table->enum('dia', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']);
            $table->string('ubicacion')->nullable();
            $table->integer('id_evento')->index('fk_eventos_horarios_evento');
            $table->text('observaciones')->nullable();
            $table->time('hora_inicio');
            $table->time('hora_fin');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_horarios');
    }
};
