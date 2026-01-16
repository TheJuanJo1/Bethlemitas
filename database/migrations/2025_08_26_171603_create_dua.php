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
        Schema::create('dua', function (Blueprint $table) {
            $table->id();
            $table->string('activity_description');
            $table->string('observation');
            $table->string('goal');
            $table->string('resource');
            $table->string('expected_results');

            // Estudiante
            $table->unsignedBigInteger('id_user_student');
            $table->foreign('id_user_student')->references('id')->on('users_students')->onDelete('cascade');

            //Docente 
            $table->unsignedBigInteger('id_teacher');
            $table->foreign('id_teacher')->references('id')->on('users_teachers');


            // Area
            $table->unsignedBigInteger('id_area');
            $table->foreign('id_area')->references('id')->on('areas')->onDelete('cascade');

            // Periodo
            $table->unsignedBigInteger('activation_period')->nullable();
            $table->foreign('activation_period')->references('id')->on('periods');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dua');
    }
};
