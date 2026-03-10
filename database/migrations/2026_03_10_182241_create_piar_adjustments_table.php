<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('piar_adjustments', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('piar_id');

            $table->integer('period');

            $table->string('area');

            $table->text('objetivo')->nullable();
            $table->text('barrera')->nullable();
            $table->text('ajuste')->nullable();
            $table->text('evaluacion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piar_adjustments');
    }
};