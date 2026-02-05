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
        Schema::table('route_markers', function (Blueprint $table) {
            $table->unique(['latitude', 'longitude', 'route_id'], 'latitude_longitude_route_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('route_markers', function (Blueprint $table) {
            $table->dropUnique('latitude_longitude_route_id_unique');
        });
    }
};
