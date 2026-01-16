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
        Schema::create('household', function (Blueprint $table) {
            $table->id();
            $table->string('support_parenting = varchar');
            $table->boolean('under_protection');
            $table->integer('num_hermanos');
            $table->integer('place_occupied');
            $table->boolean('receive_support');
            $table->string('organization_name');
            $table->string('mother_name');
            $table->string('father_name');
            $table->string('mother_occupation');
            $table->string('father_occupation');
            $table->string('mother_education_level');
            $table->string('father_education_level',);
            $table->string('live_with',);


            //Estudiante
            $table->unsignedBigInteger('id_user_student');
            $table->foreign('id_user_student')->references('id')->on('users_students')->onDelete('cascade'); 


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('household');
    }
};
