<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RecipeCategory;

class RecipeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Doces e Sobremesas',
                'slug' => 'doces-e-sobremesas',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Pratos Principais',
                'slug' => 'pratos-principais',
                'color' => '#EF4444',
            ],
            [
                'name' => 'Entradas e Aperitivos',
                'slug' => 'entradas-e-aperitivos',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Sopas e Caldos',
                'slug' => 'sopas-e-caldos',
                'color' => '#8B5CF6',
            ],
            [
                'name' => 'Massas e Risotos',
                'slug' => 'massas-e-risotos',
                'color' => '#DC2626',
            ],
            [
                'name' => 'Bebidas',
                'slug' => 'bebidas',
                'color' => '#10B981',
            ],
            [
                'name' => 'Pães e Panificação',
                'slug' => 'paes-e-panificacao',
                'color' => '#F97316',
            ],
            [
                'name' => 'Saladas',
                'slug' => 'saladas',
                'color' => '#06B6D4',
            ],
            [
                'name' => 'Molhos e Temperos',
                'slug' => 'molhos-e-temperos',
                'color' => '#84CC16',
            ],
            [
                'name' => 'Comida Vegetariana',
                'slug' => 'comida-vegetariana',
                'color' => '#22C55E',
            ],
        ];

        foreach ($categories as $category) {
            RecipeCategory::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}