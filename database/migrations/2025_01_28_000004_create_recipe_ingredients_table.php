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
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 8, 2);
            $table->string('unit', 50);
            $table->timestamps();

            // Unique constraint to prevent duplicate ingredients in the same recipe
            $table->unique(['recipe_id', 'ingredient_id'], 'unique_recipe_ingredient');

            // Indexes for better performance
            $table->index(['recipe_id']);
            $table->index(['ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};