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
        Schema::table('piar_adjustments', function (Blueprint $table) {
            $table->date('evaluation_date')->nullable()->after('autocontrol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('piar_adjustments', function (Blueprint $table) {
            $table->dropColumn('evaluation_date');
        });
    }
};
