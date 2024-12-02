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
        Schema::create('acta_piars', function (Blueprint $table) {
            $table->id();

            //Estudiante
            $table->unsignedBigInteger('id_student_characteristics');
            $table->foreign('id_student_characteristics')->references('id')->on('student_characteristics');
            //Docente
            $table->unsignedBigInteger('id_user_teacher');
            $table->foreign('id_user_teacher')->references('id')->on('users_teachers');
            //Asignatura
            $table->unsignedBigInteger('id_asignature');
            $table->foreign('id_asignature')->references('id')->on('asignatures');
            //Periodo
            $table->unsignedBigInteger('id_period');
            $table->foreign('id_period')->references('id')->on('periods');

            $table->text('overview');
            $table->text('student_personal_description');
            $table->text('areas_learning');
            $table->text('objetives_purposes');
            $table->text('barriers');
            $table->text('reasonable_adjustments');
            $table->text('adjustment_evaluation');
            $table->date('production_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acta_piars');
    }
};
