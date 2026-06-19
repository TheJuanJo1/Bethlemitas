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
        Schema::table('users_students', function (Blueprint $table) {
            $table->string('acudiente')->nullable()->after('activation_period');
            $table->string('parentesco_acudiente')->nullable()->after('acudiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_students', function (Blueprint $table) {
            $table->dropColumn(['acudiente', 'parentesco_acudiente']);
        });
    }
};
