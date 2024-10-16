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
        Schema::create('users_load_degrees', function (Blueprint $table) {
            $table->id();

            //Psicoorientador
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users_teachers');
            //Grado
            $table->unsignedBigInteger('id_degree');
            $table->foreign('id_degree')->references('id')->on('degrees');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_load_degrees');
    }
};
