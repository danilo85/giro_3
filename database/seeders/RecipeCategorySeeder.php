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
                'description' => 'Bolos, tortas, pudins, mousses e outras delícias doces',
                'is_active' => true,
            ],
            [
                'name' => 'Pratos Principais',
                'description' => 'Carnes, peixes, aves e pratos vegetarianos para refeições principais',
                'is_active' => true,
            ],
            [
                'name' => 'Entradas e Aperitivos',
                'description' => 'Petiscos, canapés, saladas e pratos para começar a refeição',
                'is_active' => true,
            ],
            [
                'name' => 'Sopas e Caldos',
                'description' => 'Sopas cremosas, caldos nutritivos e consommés',
                'is_active' => true,
            ],
            [
                'name' => 'Massas e Risotos',
                'description' => 'Massas frescas, secas, risotos e pratos com arroz',
                'is_active' => true,
            ],
            [
                'name' => 'Bebidas',
                'description' => 'Sucos, vitaminas, drinks, cafés e chás especiais',
                'is_active' => true,
            ],
            [
                'name' => 'Pães e Panificação',
                'description' => 'Pães caseiros, bolos salgados, biscoitos e produtos de panificação',
                'is_active' => true,
            ],
            [
                'name' => 'Saladas',
                'description' => 'Saladas verdes, de frutas, grãos e acompanhamentos frescos',
                'is_active' => true,
            ],
            [
                'name' => 'Molhos e Temperos',
                'description' => 'Molhos caseiros, marinadas, temperos e condimentos',
                'is_active' => true,
            ],
            [
                'name' => 'Comida Vegetariana',
                'description' => 'Pratos sem carne, veganos e vegetarianos',
                'is_active' => true,
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