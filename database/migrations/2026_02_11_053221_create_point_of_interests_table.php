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
        Schema::create('point_of_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('point_type_id')->references('id')->on('point_types')->nullOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('notes')->nullable();
            $table->decimal('latitude', 8, 6);
            $table->decimal('longitude', 8, 6);
            $table->decimal('elevation', 8, 2)->nullable();
            $table->enum('status', ['PRIVATE', 'PUBLIC'])->default('PRIVATE');
            $table->timestamp('published_at')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_of_interests');
    }
};
