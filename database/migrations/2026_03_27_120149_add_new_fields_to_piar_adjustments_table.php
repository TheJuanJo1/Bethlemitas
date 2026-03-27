<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('piar_adjustments', function (Blueprint $table) {
            $table->text('ajuste_curricular')->nullable();
            $table->text('ajuste_metodologico')->nullable();
            $table->text('ajuste_evaluativo')->nullable();
    
            $table->text('convivencia')->nullable();
            $table->text('socializacion')->nullable();
            $table->text('participacion')->nullable();
            $table->text('autonomia')->nullable();
            $table->text('autocontrol')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('piar_adjustments', function (Blueprint $table) {
            //
        });
    }
};
