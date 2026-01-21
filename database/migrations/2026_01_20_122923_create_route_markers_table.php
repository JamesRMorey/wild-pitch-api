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
        Schema::create('route_markers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->references('id')->on('routes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('latitude', 8, 6);
            $table->decimal('longitude', 8, 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_markers');
    }
};
