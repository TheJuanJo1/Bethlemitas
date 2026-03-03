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
        Schema::table('psychoorientations', function (Blueprint $table) {
            $table->string('annex_one')->nullable()->after('recomendations');
        });
    }

    public function down()
    {
        Schema::table('psychoorientations', function (Blueprint $table) {
            $table->dropColumn('annex_one');
        });
    }
};
