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
        Schema::create('piar_annual_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('piar_id')->unique();
            $table->foreign('piar_id')->references('id')->on('piar')->onDelete('cascade');
            $table->text('competencies')->nullable();
            $table->text('aspects')->nullable();
            $table->text('behavior_observation')->nullable();
            $table->text('academic_observation')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piar_annual_reports');
    }
};
