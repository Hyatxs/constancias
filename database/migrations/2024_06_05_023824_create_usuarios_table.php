<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); // Si no especificas el nombre de la columna, Laravel por defecto usará 'id'
            $table->string('nombre', 50);
            $table->string('apellido_paterno', 50)->nullable();  // Permite que apellido_paterno sea NULL
            $table->string('apellido_materno', 50)->nullable();  // Permite que apellido_materno sea NULL
            $table->string('email', 50);
            $table->string('password');
            $table->enum('rol', ['Administrador', 'Maestro', 'Estudiante', 'Director', 'Coordinador']);
            $table->string('matricula', 25)->nullable(); // Si deseas que matricula también pueda ser NULL
            $table->string('telefono', 15)->nullable(); // Si deseas que telefono también pueda ser NULL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
