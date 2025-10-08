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
        if (!Schema::hasTable('recipes')) {
            Schema::create('recipes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('category_id')->nullable()->constrained('recipe_categories')->onDelete('set null');
                $table->string('name');
                $table->text('description')->nullable();
                $table->text('preparation_method');
                $table->integer('preparation_time')->comment('Tempo em minutos');
                $table->integer('servings');
                $table->string('image_path', 500)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                // Indexes for better performance
                $table->index(['user_id']);
                $table->index(['category_id']);
                $table->index(['name']);
                $table->index(['preparation_time']);
                $table->index(['is_active']);
                $table->index(['user_id', 'is_active']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};