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
            $table->unsignedBigInteger('id_state');
            $table->foreign('id_state')->references('id')->on('states');
            //Periodo
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
        Schema::dropIfExists('users_students');
    }
};
