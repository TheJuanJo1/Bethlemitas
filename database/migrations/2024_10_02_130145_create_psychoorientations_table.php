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
        Schema::create('psychoorientations', function (Blueprint $table) {
            $table->id();

            //Psicologa
            $table->unsignedBigInteger('psychologist_writes');
            $table->foreign('psychologist_writes')->references('id')->on('users_teachers');
            //Estudiante
            $table->unsignedBigInteger('id_user_student');
            $table->foreign('id_user_student')->references('id')->on('users_students');

            $table->string('title_report');
            $table->text('reason_inquiry');
            $table->text('recomendations');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychoorientations');
    }
};
