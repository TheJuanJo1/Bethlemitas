<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('piar_minutes', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('id_user_student');
            $table->unsignedBigInteger('teacher_id');

            $table->string('act_number')->nullable();
            $table->date('act_date');

            $table->string('act_file');

            $table->timestamps();

            $table->foreign('id_user_student')->references('id')->on('users_students')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users_teachers')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piar_minutes');
    }
};