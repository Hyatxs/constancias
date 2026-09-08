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
        Schema::create('evidencias_eventos', function (Blueprint $table) {
            $table->id('id_evidencia');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_evento');
            $table->string('archivo');
            $table->dateTime('fecha_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidencias_eventos');
    }
};