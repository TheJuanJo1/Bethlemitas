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
        Schema::table('piar_adjustments', function (Blueprint $table) {
            // Verificamos si la columna existe antes de crearla para evitar errores
            if (!Schema::hasColumn('piar_adjustments', 'teacher_id')) {
                $table->unsignedBigInteger('teacher_id')->after('period')->nullable();

                $table->foreign('teacher_id')
                      ->references('id')
                      ->on('users_teachers')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('piar_adjustments', function (Blueprint $table) {
            if (Schema::hasColumn('piar_adjustments', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
                $table->dropColumn('teacher_id');
            }
        });
    }
};
