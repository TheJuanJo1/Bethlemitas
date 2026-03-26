<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToPiarAdjustments extends Migration
{
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

    public function down()
    {
        Schema::table('piar_adjustments', function (Blueprint $table) {

            $table->dropColumn([
                'ajuste_curricular',
                'ajuste_metodologico',
                'ajuste_evaluativo',
                'convivencia',
                'socializacion',
                'participacion',
                'autonomia',
                'autocontrol',
            ]);

        });
    }
}