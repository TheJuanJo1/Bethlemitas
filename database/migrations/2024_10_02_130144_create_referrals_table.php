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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();

            //Estudiante
            $table->unsignedBigInteger('id_user_student');
            $table->foreign('id_user_student')->references('id')->on('users_students');
            //Docente
            $table->unsignedBigInteger('id_user_teacher');
            $table->foreign('id_user_teacher')->references('id')->on('users_teachers');

            $table->text('reason');
            $table->text('observation');
            $table->text('strategies');
            $table->date('submission_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
