<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('piar_characteristics', function (Blueprint $table) {
            $table->text('descripcion_estudiante')->nullable()->change();
            $table->text('gustos_intereses')->nullable()->change();
            $table->text('expectativas_familia')->nullable()->change();
            $table->text('habilidades')->nullable()->change();
            $table->text('apoyos_requeridos')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('piar_characteristics', function (Blueprint $table) {
            $table->text('descripcion_estudiante')->nullable(false)->change();
            $table->text('gustos_intereses')->nullable(false)->change();
            $table->text('expectativas_familia')->nullable(false)->change();
            $table->text('habilidades')->nullable(false)->change();
            $table->text('apoyos_requeridos')->nullable(false)->change();
        });
    }
};
