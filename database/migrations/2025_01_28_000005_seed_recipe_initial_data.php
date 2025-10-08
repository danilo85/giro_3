<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert recipe categories
        DB::table('recipe_categories')->insert([
            ['name' => 'Doces', 'slug' => 'doces', 'color' => '#F59E0B', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Salgados', 'slug' => 'salgados', 'color' => '#EF4444', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bebidas', 'slug' => 'bebidas', 'color' => '#3B82F6', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Massas', 'slug' => 'massas', 'color' => '#8B5CF6', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Carnes', 'slug' => 'carnes', 'color' => '#DC2626', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vegetarianos', 'slug' => 'vegetarianos', 'color' => '#10B981', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sobremesas', 'slug' => 'sobremesas', 'color' => '#F97316', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lanches', 'slug' => 'lanches', 'color' => '#06B6D4', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insert common ingredients
        DB::table('ingredients')->insert([
            ['name' => 'Farinha de trigo', 'default_unit' => 'g', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Açúcar', 'default_unit' => 'g', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ovos', 'default_unit' => 'unidade', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Leite', 'default_unit' => 'ml', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manteiga', 'default_unit' => 'g', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sal', 'default_unit' => 'g', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fermento em pó', 'default_unit' => 'g', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Óleo', 'default_unit' => 'ml', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cebola', 'default_unit' => 'unidade', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alho', 'default_unit' => 'dente', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tomate', 'default_unit' => 'unidade', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Queijo mussarela', 'default_unit' => 'g', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Presunto', 'default_unit' => 'g', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Arroz', 'default_unit' => 'g', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Feijão', 'default_unit' => 'g', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Frango', 'default_unit' => 'g', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Carne bovina', 'default_unit' => 'g', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Batata', 'default_unit' => 'unidade', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cenoura', 'default_unit' => 'unidade', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pimentão', 'default_unit' => 'unidade', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('recipe_ingredients')->truncate();
        DB::table('recipes')->truncate();
        DB::table('ingredients')->truncate();
        DB::table('recipe_categories')->truncate();
    }
};