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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('notes')->nullable();
            $table->decimal('latitude', 8, 6);
            $table->decimal('longitude', 8, 6);
            $table->decimal('distance', 10, 2)->nullable();
            $table->enum('status', ['PRIVATE', 'PUBLIC'])->default('PRIVATE');
            $table->decimal('elevation_gain', 10, 2)->nullable();
            $table->decimal('elevation_loss', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
