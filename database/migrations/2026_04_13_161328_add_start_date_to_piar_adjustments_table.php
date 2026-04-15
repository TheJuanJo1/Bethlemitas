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
            $table->date('start_date')->nullable()->after('ajuste_evaluativo');
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
