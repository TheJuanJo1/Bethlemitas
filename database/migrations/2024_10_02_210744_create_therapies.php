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
        Schema::create('therapies', function (Blueprint $table) {
            $table->id();
            $table->string('therapy_type');
            $table->string('frequency');
            

            // Salud
            $table->unsignedBigInteger('id_health');
            $table->foreign('id_health')->references('id')->on('healths')->onDelete('cascade');
            
            // Estudiante
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
        Schema::dropIfExists('therapies');
    } 
};
