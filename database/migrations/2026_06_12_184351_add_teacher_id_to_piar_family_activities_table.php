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
        Schema::table('piar_family_activities', function (Blueprint $table) {
            if (!Schema::hasColumn('piar_family_activities', 'teacher_id')) {
                $table->unsignedBigInteger('teacher_id')->nullable()->after('piar_id');
            }
            
            // To prevent key constraint duplicate error, we can try to drop constraint if it might exist,
            // but since the previous run failed on foreign key, it shouldn't exist.
            $table->foreign('teacher_id')->references('id')->on('users_teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('piar_family_activities', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};
