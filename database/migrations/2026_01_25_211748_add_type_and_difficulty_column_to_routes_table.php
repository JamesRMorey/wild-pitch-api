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
        Schema::table('routes', function (Blueprint $table) {
            $table->enum('type', ['CIRCULAR', 'POINT_TO_POINT', 'OUT_AND_BACK', 'UNKNOWN'])->default('UNKNOWN')->after('elevation_loss');
            $table->enum('difficulty', ['EASY', 'MODERATE', 'CHALLENGING', 'DIFFICULT', 'UNKNOWN'])->default('UNKNOWN')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('difficulty');
        });
    }
};
