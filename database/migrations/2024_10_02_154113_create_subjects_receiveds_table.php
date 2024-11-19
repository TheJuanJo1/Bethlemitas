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
        Schema::create('subjects_receiveds', function (Blueprint $table) {
            $table->id();

            //Grupo
            $table->unsignedBigInteger('id_group');
            $table->foreign('id_group')->references('id')->on('groups')->onDelete('cascade');
            //Asignatura
            $table->unsignedBigInteger('id_asignature');
            $table->foreign('id_asignature')->references('id')->on('asignatures')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects_receiveds');
    }
};
