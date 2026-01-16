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
        Schema::create('recommendation', function (Blueprint $table) {
            $table->id();
            $table->string('involved_actor');
            $table->string('strategies');

            // Profesores 
            $table->unsignedBigInteger('id_user_teacher');
            $table->foreign('id_user_teacher')->references('id')->on('users_teachers')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation');
    }
};
