<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users_students', function (Blueprint $table) {
            $table->bigInteger('number_documment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_students', function (Blueprint $table) {
            $table->bigInteger('number_documment')->nullable(false)->change();
        });
    }
};
