<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('piar', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_id');

            $table->year('year');

            $table->string('institution')->nullable();
            $table->string('sede')->nullable();
            $table->string('jornada')->nullable();

            $table->timestamps();

            // relaciones
            $table->foreign('student_id')
                  ->references('id')
                  ->on('users_students')
                  ->onDelete('cascade');

            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('users_teachers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piar');
    }
};