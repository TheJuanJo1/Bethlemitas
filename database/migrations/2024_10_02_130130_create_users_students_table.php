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
        Schema::create('users_students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('number_documment')->unique();
            $table->string('name');
            $table->string('last_name');
            $table->tinyInteger('age')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('address')->nullable();
            $table->enum('type_documment', ['TI', 'CC', 'RC'])->default('TI'); 
            $table->bigInteger('phone')->nullable();
            $table->string('email')->unique()->nullable();
            $table->boolean('victim_conflict')->default(false);



            //Grado
            $table->unsignedBigInteger('id_degree')->nullable();
            $table->foreign('id_degree')->references('id')->on('degrees')->onDelete('set null');
            //Grupo
            $table->unsignedBigInteger('id_group')->nullable();
            $table->foreign('id_group')->references('id')->on('groups')->onDelete('set null');
            //Docente
            $table->unsignedBigInteger('sent_by')->nullable();
            $table->foreign('sent_by')->references('id')->on('users_teachers');
            //Estado
            $table->unsignedBigInteger('id_state')->nullable();
            $table->foreign('id_state')->references('id')->on('states');
            //Periodo
            $table->unsignedBigInteger('activation_period')->nullable();
            $table->foreign('activation_period')->references('id')->on('periods');
            //Cuidador
            $table->unsignedBigInteger('id_carer')->nullable();
            $table->foreign('id_carer')->references('id')->on('carer')->onDelete('set null');
            //Departamento
            $table->unsignedBigInteger('id_deparment')->nullable();
            $table->foreign('id_deparment')->references('id')->on('deparments');
            //Municipio
            $table->unsignedBigInteger('id_municipality')->nullable();
            $table->foreign('id_municipality')->references('id')->on('municipalities')->onDelete('set null');
            //Grupo Etnico
            $table->unsignedBigInteger('id_ethnic_group')->nullable();
            $table->foreign('id_ethnic_group')->references('id')->on('ethnic_group')->onDelete('set null');
            //Institucion
            $table->unsignedBigInteger('id_institution')->nullable();
            $table->foreign('id_institution')->references('id')->on('institution')->onDelete('set null');
            



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_students');
    }
};
