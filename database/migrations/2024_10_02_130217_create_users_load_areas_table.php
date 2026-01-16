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
        Schema::create('users_load_areas', function (Blueprint $table) {
            $table->id();

            //Docente
            $table->unsignedBigInteger('id_user_teacher');
            $table->foreign('id_user_teacher')->references('id')->on('users_teachers');
            //Areas
            $table->unsignedBigInteger('id_area');
            $table->foreign('id_area')->references('id')->on('areas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_load_areas');
    }
};
