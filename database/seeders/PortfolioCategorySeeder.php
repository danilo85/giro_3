<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PortfolioCategory;
use App\Models\User;

class PortfolioCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Nenhum usuário encontrado. Execute UserSeeder primeiro.');
            return;
        }

        $categorias = [
            [
                'name' => 'Desenvolvimento Web',
                'slug' => 'desenvolvimento-web',
                'description' => 'Projetos de desenvolvimento de sites e aplicações web',
                'color' => '#3B82F6',
                'icon' => 'code',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Design Gráfico',
                'slug' => 'design-grafico',
                'description' => 'Criação de identidades visuais, logotipos e materiais gráficos',
                'color' => '#EC4899',
                'icon' => 'palette',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Marketing Digital',
                'slug' => 'marketing-digital',
                'description' => 'Campanhas e estratégias de marketing digital',
                'color' => '#10B981',
                'icon' => 'megaphone',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Fotografia',
                'slug' => 'fotografia',
                'description' => 'Sessões fotográficas e produção de conteúdo visual',
                'color' => '#F59E0B',
                'icon' => 'camera',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Consultoria',
                'slug' => 'consultoria',
                'description' => 'Serviços de consultoria empresarial e estratégica',
                'color' => '#8B5CF6',
                'icon' => 'users',
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Audiovisual',
                'slug' => 'audiovisual',
                'description' => 'Produção de vídeos, motion graphics e conteúdo audiovisual',
                'color' => '#EF4444',
                'icon' => 'video',
                'is_active' => true,
                'sort_order' => 6
            ]
        ];

        // Criar categorias para cada usuário
        foreach ($users as $user) {
            foreach ($categorias as $categoriaData) {
                $slugUnico = $categoriaData['slug'] . '-' . $user->id;
                PortfolioCategory::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'slug' => $slugUnico
                    ],
                    array_merge($categoriaData, [
                        'user_id' => $user->id,
                        'slug' => $slugUnico
                    ])
                );
            }
        }

        $this->command->info('Categorias de portfólio criadas com sucesso!');
        $this->command->info('Total de categorias criadas: ' . (count($categorias) * $users->count()));
        $this->command->info('Distribuídas entre ' . $users->count() . ' usuários.');
    }
}