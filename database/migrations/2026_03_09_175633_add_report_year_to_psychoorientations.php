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
        Schema::table('psychoorientations', function (Blueprint $table) {

            // 🔹 Año del informe psicológico
            $table->year('report_year')->after('id_user_student');

            // 🔹 Evita que un estudiante tenga más de un informe por año
            $table->unique(['id_user_student', 'report_year'], 'student_year_unique_report');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psychoorientations', function (Blueprint $table) {

            // eliminar índice
            $table->dropUnique('student_year_unique_report');

            // eliminar columna
            $table->dropColumn('report_year');

        });
    }
};