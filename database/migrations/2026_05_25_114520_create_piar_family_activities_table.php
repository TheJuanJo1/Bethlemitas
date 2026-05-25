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
        Schema::create('piar_family_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('piar_id');
            $table->foreign('piar_id')->references('id')->on('piar')->onDelete('cascade');
            $table->integer('period');
            $table->text('activity')->nullable();
            $table->text('strategy')->nullable();
            $table->string('frequency')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piar_family_activities');
    }
};
