<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            // Nullable porque los eventos que ya existen no tienen profesor asignado.
            $table->unsignedBigInteger('id_maestro')->nullable()->after('id_director');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('id_maestro');
        });
    }
};
