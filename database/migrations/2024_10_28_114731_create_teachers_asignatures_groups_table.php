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
        Schema::create('teachers_asignatures_groups', function (Blueprint $table) {
            $table->id();
            //Docente FK
            $table->unsignedBigInteger('id_teacher');
            $table->foreign('id_teacher')->references('id')->on('users_teachers');
            //Asignatura FK
            $table->unsignedBigInteger('id_asignature');
            $table->foreign('id_asignature')->references('id')->on('asignatures');
            //Grupo FK
            $table->unsignedBigInteger('id_group');
            $table->foreign('id_group')->references('id')->on('groups')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers_asignatures_groups');
    }
};
