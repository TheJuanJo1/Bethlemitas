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
        Schema::create('healths', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_affiliated');
            $table->string('eps');
            $table->string('affiliation_type');
            $table->string('emergency_location');
            $table->boolean('receives_medical_care');
            $table->string('care_frequency');
            $table->string('medical_diagnosis');
            $table->boolean('has_diagnosis');
            $table->boolean('takes_medication');
            $table->string('medication_description');
            $table->boolean('uses_assistive_products');
            $table->string('assistive_product_description');


            
            //Estudiante
            $table->unsignedBigInteger('id_student');
            $table->foreign('id_student')->references('id')->on('users_students')->onDelete('cascade'); 
            
            
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('healths');
    }
};
