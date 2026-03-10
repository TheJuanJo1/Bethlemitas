<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('piar_characteristics', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('piar_id');

            $table->text('descripcion_estudiante');
            $table->text('gustos_intereses');
            $table->text('expectativas_familia');
            $table->text('habilidades');
            $table->text('apoyos_requeridos');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piar_characteristics');
    }
};