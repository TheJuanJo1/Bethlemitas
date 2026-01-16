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
        Schema::create('annual_report', function (Blueprint $table) {
            $table->id();
            $table->year('academic_year');     
            $table->string('competencies');
            $table->string('aspect');
            $table->string('observation');
            $table->string('performance_observation');
            $table->string('recommendation');
            
            // Estudiante
            $table->unsignedBigInteger('id_user_student');
            $table->foreign('id_user_student')->references('id')->on('users_students')->onDelete('cascade');

            // Profesor
            $table->unsignedBigInteger('id_user_teacher');
            $table->foreign('id_user_teacher')->references('id')->on('users_teachers')->onDelete('cascade');

            // Grado
            $table->unsignedBigInteger('id_degree')->nullable();
            $table->foreign('id_degree')->references('id')->on('degrees')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_report');
    }
};
